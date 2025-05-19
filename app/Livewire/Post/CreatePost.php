<?php

namespace App\Livewire\Post;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use Livewire\Component;
use App\Models\Category;
use Detection\Cache\Cache;
use Illuminate\Support\Str;
use App\Enums\PostStatusEnum;
use Livewire\WithFileUploads;
use App\Rules\MaxLinksAllowed;
use App\Rules\MaxWordsAllowed;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Services\PostLogsService;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\InteractsWithBanner;
use Intervention\Image\Laravel\Facades\Image;

class CreatePost extends Component
{
    use InteractsWithBanner, WithFileUploads;

    public $title;
    public $slug;
    public $meta_title;
    public $meta_description;
    public $content;
    public $images;
    public $postId;

    #[Locked]
    public $post;

    private $postStatus = PostStatusEnum::DRAFT;

    public $featured_image;
    public $selectedCategories = [];
    public $categories = [];
    public $tags = [];
    public $selectedTags = [];
    public $contentWords = '';
    public $showPublishPostModal = false;
    public $showPublishPostButton = false;
    public $featurePost = false;
    private $postCreated = false;
    public $fileName; 
    public $latestScan = [];
    public $savePublish = false;

    public $event_type;
    public $event_for;
    public $event_cost;
    public $meeting_link;
    public $venue;
    public $entry_code;

    protected $listeners = ['clearError', 'showPublishModal'];
    protected $rules = [
        'title' => 'required|string|max:200',
        'meta_title' => 'required|string|max:150',
        'meta_description' => 'required|string|max:150',
        'content' => ['required', 'string'],
        'featured_image' => 'required|image|max:5120|mimes:jpeg,png,jpg,gif,svg', // Removed strict dimensions
        'selectedCategories' => 'required|array|min:1',
        'selectedTags' => 'required|array|min:1',
    ];

    protected $messages = [
        'selectedCategories.required' => 'Please select at least one category.',
        'selectedTags.required' => 'Please give at least one tag.',
    ];
    public function clearError($field)
    {
        $this->resetErrorBag($field);
    }
    public function updatedFeaturedImage()
    {
        if ($this->featured_image) {
            $this->fileName = $this->featured_image->getClientOriginalName();
            $this->resetErrorBag('featured_image');
        }
    }

    // Mount method to initialize component state
    public function mount($post = null)
    {
        $this->postCreated = false;
        //Check if post owner is the authenticated user
        if ($post && $post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        if ($post) {
            if($post->status->value === PostStatusEnum::REVISION->value){
                $this->getLatestPlagiarismReport();
            }
            $this->showPublishPostButton =
                ($this->post->publish_attempts == 3 && Carbon::now()->lt($this->post->publish_time_limit) && Carbon::now()->diffInHours($this->post->publish_time_limit) <= 24) || $this->post->status->value == 'in_review'
                ? false
                : true;

            $this->postId = $post->id;
            $this->post = $post;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->meta_title = $post->meta_title;
            $this->meta_description = $post->meta_description;
            $this->content = $post->content();
            $this->selectedCategories = $post->categories->pluck('id')->toArray();
            $this->selectedTags = $post->tags->pluck('name')->toArray();
        }
        $this->categories = Category::all();
        $this->tags = Tag::all();
    }
    private function getLatestPlagiarismReport(){
        $this->latestScan = $this->post->plagiarismScanHistories()->latest()->first();
    }
    public function validatePost()
    {
        $rules = [
            'content' => [
                'required', 
                'string', 
                new MaxWordsAllowed((int)getSettingValue( 'post-content-limit') ?? 1500), 
                new MaxLinksAllowed((int)getSettingValue('post-links-limit') ?? 3)
            ],
            'title' => 'required|string|max:200',
            'meta_title' => 'required|string|max:150',
            'meta_description' => 'required|string|max:' . (getSettingValue('post-description-limit') ?? 150),
            'selectedCategories' => 'required|array|min:1',
            'selectedTags' => 'required|array|min:1',
             // 👇 Event fields
            'event_type' => 'required|in:virtual,physical',
            'event_for' => 'required|in:public,members',
            'event_cost' => 'required|in:free,paid',

        ];
        // Conditional fields
        if ($this->event_type === 'virtual') {
            $rules['meeting_link'] = 'required|string|max:255';
        }

        if ($this->event_type === 'physical') {
            $rules['venue'] = 'required|string|max:255';
            $rules['entry_code'] = 'nullable|string|max:100';
        }
        if (!$this->postId && $this->featured_image == null) {
            $this->fileName = null;
            $rules['featured_image'] = 'required|image|max:5120|mimes:jpeg,png,jpg,gif,svg';
        }
        if ($this->postId && empty($this->post->featured_image_path)) {
            // Case: Updating post & featured image is removed → Image is required again
            $this->fileName = null;
            $rules['featured_image'] = 'required|image|max:5120|mimes:jpeg,png,jpg,gif,svg';
        }
        
        $this->validate($rules, [
            'selectedCategories.required' => 'Please select at least one category.',
            'selectedTags.required' => 'Please provide at least one tag.',
            'meeting_link.required' => 'Meeting link is required for virtual events.',
            'venue.required' => 'Venue is required for physical events.',
        ]);
    }

    public function eventTypeUpdated()
    {
        logger('Event Type Updated: ' . $this->event_type);

        $this->reset([
            'event_for',
            'event_cost',
            'meeting_link',
            'venue',
            'entry_code',
        ]);
    }

    public function eventForUpdated()
    {
        logger('Event For Updated: ' . $this->event_for);

        $this->reset([
            'event_cost',
            'meeting_link',
            'venue',
            'entry_code',
        ]);
    }

    public function eventCostUpdated()
    {
        logger('Event Cost Updated: ' . $this->event_cost);

        if ($this->event_type === 'virtual') {
            $this->reset(['meeting_link']);
        }

        if ($this->event_type === 'physical') {
            $this->reset(['venue', 'entry_code']);
        }
    }

    public function saveDraft()
    {
        if($this->post && $this->post->status->value === 'in_review'){
            $this->dispatch('notify', title: 'Under Review Post', message: 'your post is under review, cant be updated.', type: 'error');
            return ;
        }
        if (trim($this->content) === "<p><br></p>") {
            $this->content = "";
        }
    
        $this->validatePost();   
        
        if (!$this->postId) {
            $this->slug = Str::slug($this->title);
            $this->validate([
                'slug' => Rule::unique('posts', 'slug'),
            ]);
        }       
        $contentHtml = $this->content;
        
        $this->content = $this->contentWords;
        
        $this->content = $contentHtml;
        //Calculate excerpt characters
        $excerpt = Str::of($this->contentWords)->take(150)->value();
        $this->title = Str::title($this->title);
        if ($this->postId) {
            \Cache::forget('related_posts_' . $this->post->id);
            $post = Post::find($this->postId);
            $excerpt = $post->excerpt; // Keep old excerpt by default
            if ($post->content()                                                                                                                                 !== $this->content) {
                $excerpt = Str::of($this->contentWords)->take(150)->value(); 
            }
            $post->update([
                'title' => $this->title,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                // 'status' => $this->postStatus,
                'excerpt' => $excerpt,
                // 'user_id' => auth()->id(),

                // 👇 Event fields
                'event_type' => $this->event_type,
                'event_for' => $this->event_for,
                'event_cost' => $this->event_cost,
                'meeting_link' => $this->meeting_link,
                'venue' => $this->venue,
                'entry_code' => $this->entry_code,
            ]);
            $this->createPostLog('Post is updated.');
        } else {
            $this->postCreated = true;
            $post = Post::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'status' => $this->postStatus,
                'excerpt' => $excerpt,
                'user_id' => auth()->id(),

                // 👇 Event fields
                'event_type' => $this->event_type,
                'event_for' => $this->event_for,
                'event_cost' => $this->event_cost,
                'meeting_link' => $this->meeting_link,
                'venue' => $this->venue,
                'entry_code' => $this->entry_code,
            ]);
            $this->postId = $post->id;
            $this->createPostLog('Post submitted as draft');
        }
        $post->categories()->sync($this->selectedCategories);
        $this->post = $post;

        $this->saveContentToFile($post);
        $this->syncTags($post);
        $this->saveFeaturedImage($post);
       
        if($this->postCreated && $this->savePublish == true){
            $this->dispatch('notify', title: 'Post is created Successfully', message: 'Post Created successfully.', type: 'success');            
        }
        elseif($this->savePublish == true){
            $this->dispatch('notify', title: 'Post is updated Successfully', message: 'Post Updated successfully.', type: 'success');            
        }
        else{
            session()->flash('success', 'Post Saved as Draft Successfully.');
            return redirect()->route('dashboard');            
        }
    }

    public function saveContentToFile($post)
    {
        if ($post->content_file_path && Storage::disk('local')->exists($post->content_file_path)) {
            $filePath = $post->content_file_path;
        } else {
            $fileName = 'postdata_' . $post->id . '.json';
            $filePath = 'posts/' . $fileName;
            $post->update(['content_file_path' => $filePath]);
        }
        // Fetch the content data from the file and update ony content and merge the old logs data
        $postData = json_decode(Storage::disk('local')->get($filePath), true);
        $postData['content'] = $this->content;
        Storage::disk('local')->put($filePath, json_encode($postData, JSON_PRETTY_PRINT));
    }

    public function saveFeaturedImage($post)
    {
        if ($this->featured_image) {
            if ($post->featured_image_path && Storage::disk('public')->exists($post->featured_image_path)) {
                Storage::disk('public')->delete($post->featured_image_path);
            }

            $imageName = 'featured_' . $post->id . '_' . Str::random(10) . '.' . $this->featured_image->getClientOriginalExtension();

            $featureImage = Image::read($this->featured_image);
            $featureImage->resize(1200, 686, function ($constraint) {
                $constraint->aspectRatio(); // Maintain aspect ratio
                $constraint->upsize();      // Prevent upscaling if the image is smaller
            });

            // Save the resized featured image to storage
            $imagePath = 'featured_images/' . $imageName;
            $fullImagePath = storage_path('app/public/' . $imagePath);

            // ✅ Ensure the directory exists
            if (!file_exists(dirname($fullImagePath))) {
                mkdir(dirname($fullImagePath), 0755, true);
            }

            // ✅ Now save the image safely
            $featureImage->save($fullImagePath);
            // Update the post with the path to the resized featured image
            $post->update(['featured_image_path' => $imagePath]);

            // $imagePath = $this->featured_image->storeAs('featured_images', $imageName, 'public');
            // $post->update(['featured_image_path' => $imagePath]);

            // Create and save the thumbnail image
            $thumbnailName = 'thumbnail_' . $post->id . '_' . Str::random(10) . '.' . $this->featured_image->getClientOriginalExtension();
            $thumbnailPath = 'thumbnails/' . $thumbnailName;

            // Read the image file
            $image = Image::read($this->featured_image);

            // Resize the image to fit within 350x200 pixels while maintaining aspect ratio
            $image->resize(350, 200, function ($constraint) {
                $constraint->aspectRatio(); // Maintain aspect ratio
                $constraint->upsize();      // Prevent upscaling if the image is smaller
            });

            // Save the resized thumbnail image to storage
            Storage::disk('public')->put($thumbnailPath, $image->encode());

            // Save the thumbnail path to the database
            $post->update(['thumbnail_image_path' => $thumbnailPath]);
        }
    }

    public function deleteFeaturedImage()
    {
        if ($this->post && $this->post->featured_image_path && Storage::disk('public')->exists($this->post->featured_image_path)) {
            Storage::disk('public')->delete($this->post->featured_image_path);
            $this->post->update(['featured_image_path' => null]);
            $this->featured_image = null;
        }
    }

    public function savePost()
    {
        $this->saveDraft();
    }

    public function publishPost()
    {
        $user = auth()->user();
        if (!$this->post) {
            return; // Ensure the post exists
        }
        $this->savePost();
        
        $this->post->status = PostStatusEnum::PUBLISHED;
    
        // If the post is a featured post, set the `is_feature` flag to true
        if ($this->featurePost) {
            $this->post->is_feature = true;
        }
        // Save the updated post
        $this->post->save();
        $this->createPostLog("Post is Published Successfully.");
        session()->flash('success', 'Post is Published Successfully');
        return redirect()->route('home');
    }

    private function createPostLog($action)
    {
        // Create a log for the post
        $postLogService = new PostLogsService();
        $postLogService->createLog($this->postId, $action, auth()->id());
    }

    private function syncTags($post)
    {
        // Sync selected tags with the post
        $tagIds = [];

        foreach ($this->selectedTags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]);
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);
    }

    public function showPublishModal()
    {
        try {
            $this->validatePost();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $validationErrors = $e->errors(); // e.g. ['title' => ['The title field is required.']]
    
            // Optional log
            \Log::error('Validation Exception: ', $validationErrors);
    
            // Loop through each field and its messages
            foreach ($validationErrors as $field => $messages) {
                foreach ($messages as $message) {
                    $this->dispatch('notify', 
                        title: ucfirst(str_replace('_', ' ', $field)), // e.g. "Post Body"
                        message: $message,
                        type: 'error'
                    );
                }
            }
    
            return;
        }
    
        $this->savePublish = true;
        $this->showPublishPostModal = true;
    }
    
    public function render()
    {
        return view('livewire.post.create-post', ['content' => $this->content]);
    }
}
