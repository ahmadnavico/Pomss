<?php

namespace App\Livewire\Post;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Enums\PostStatusEnum;
use Livewire\WithFileUploads;
use App\Rules\MaxLinksAllowed;
use App\Rules\MaxWordsAllowed;
use Livewire\Attributes\Locked;
use App\Services\PostLogsService;
use App\Events\Post\PostStatusChange;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\InteractsWithBanner;
use Intervention\Image\Laravel\Facades\Image;

class EditPost extends Component
{
    use InteractsWithBanner, WithFileUploads;
    #[Locked]
    public Post $post;
    public $formData = [];
    public $categories = [];
    public $tags = [];
    public $selectedCategories = [];
    public $selectedTags = [];
    public $featured_image;
    public $contentWords = '';
    public $statuses = [];
    public $fileName; 
    public $latestScan = [];
    public $showPublishPostModal = false;
    private $postStatus = "";
    public $statusChangeReason = '';
    // Validation rules
    public $featurePost = false; 
    
    protected $rules = [
        'formData.title' => 'required|string|max:200',
        'formData.meta_title' => 'required|string|max:150',
        'formData.meta_description' => 'required|string|max:150',
        'formData.content' => ['required', 'string'],
        'featured_image' => 'required|image|max:5120|mimes:jpeg,png,jpg,gif,svg',
        'selectedCategories' => 'required|array|min:1',
        'selectedTags' => 'required|array|min:1',
    ];

    // Custom validation messages
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
    public function mount(Post $post)
    {
        $this->post = $post;
        $this->featurePost = (bool) $post->is_feature;
        $this->setFormData($post);
        $this->getAllCategories();
        $this->getAllStatuses();
        $this->getAllTags();
        $this->selectedTags = $post->tags->pluck('name')->toArray();
        
    }

    public function validatePost()
    {
        $rules = [
            'formData.content' => [
                'required', 
                'string', 
                new MaxWordsAllowed((int)getSettingValue( 'post-content-limit') ?? 1500), 
                new MaxLinksAllowed((int)getSettingValue('post-links-limit') ?? 3)
            ],
            'formData.title' => 'required|string|max:200',
            'formData.meta_title' => 'required|string|max:150',
            'formData.meta_description' => 'required|string|max:' . (getSettingValue('post-description-limit') ?? 150),
            'selectedCategories' => 'required|array|min:1',
            'selectedTags' => 'required|array|min:1',
        ];
        if ($this->featured_image == null && $this->post->featured_image_path == null) {
            $this->fileName = null;
            $rules['featured_image'] = 'required|image|max:5120|mimes:jpeg,png,jpg,gif,svg';
        }
        $this->validate($rules, [
            'selectedCategories.required' => 'Please select at least one category.',
            'selectedTags.required' => 'Please provide at least one tag.',
        ]);
        
    }
    // Method to save the post
    public function savePost()
    {
        $this->validatePost();
       
        $contentHtml = $this->formData['content'];
        $this->formData['content'] = $this->contentWords;
      
        $this->formData['content'] = $contentHtml;
        $excerpt = Str::of($this->contentWords)->take(150)->value();
        $this->formData['title'] = Str::title($this->formData['title']);
        // Check if the slug has been updated (only admins can edit the slug)
        $slugChanged = false;
        if ($this->post->slug !== $this->formData['slug']) {
            $slugChanged = true;
            $this->formData['slug'] = Str::slug($this->formData['slug']); // Ensure the slug is properly formatted
        }

        $statusChanged = $this->post->status !== $this->formData['status'];
        
        \Cache::forget('related_posts_' . $this->post->id);
        $this->post->update([
            'title' => $this->formData['title'],
            'meta_title' => $this->formData['meta_title'],
            'meta_description' => $this->formData['meta_description'],
            'status' => $this->formData['status'],
            'excerpt' => $excerpt,
            'slug' => $this->formData['slug']
            // 'user_id' => auth()->id(),
        ]);

        $this->post->categories()->sync($this->selectedCategories);
        $this->saveContentToFile(post: $this->post);
        $this->saveFeaturedImage($this->post);
        $this->syncTags(post: $this->post);
        $this->createPostLog('Post is updated.');
        
        return redirect()->route('home');
    }
    
    // Method to save content to a file
    private function saveContentToFile($post)
    {
        if ($post->content_file_path && Storage::disk('local')->exists($post->content_file_path)) {
            $filePath = $post->content_file_path;
        } else {
            $fileName = 'post_' . $post->id . '_' . auth()->id() . '_' . Str::random(10) . '.json';
            $filePath = 'posts/' . $fileName;
            $post->update(['content_file_path' => $filePath]);
        }
        // Prepare the data to be saved in the file
        // Prepare the data to be saved in the file
        $postData = json_decode(Storage::disk('local')->get($filePath), true);
        $postData['content'] = $this->formData['content'];
        Storage::disk('local')->put($filePath, json_encode($postData, JSON_PRETTY_PRINT));
    }

    // Method to save the featured image
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

    // Method to sync tags
    private function syncTags($post)
    {
        $tagIds = [];

        foreach ($this->selectedTags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]);
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);
        $this->dispatch('notify', title: 'Post updated', message: 'Post updated successfully.', type: 'success');
    }

    // Method to set form data from the post
    private function setFormData($post)
    {
        $this->formData = $post->only(['title', 'slug', 'meta_title', 'meta_description', 'featured_image', 'status']);
        $this->formData['content'] = $post->content();
        $this->formData['logs'] = collect($post->getLogs())->map(function ($log) {
            return is_array($log) ? (object) $log : $log;
        });
        $this->selectedCategories = $post->categories->pluck('id')->toArray();
        $this->selectedTags = $post->tags->pluck('name')->toArray();
    }

    // Method to get all categories
    private function getAllCategories()
    {
        $this->categories = Category::all();
    }

    // Method to get all tags
    private function getAllTags()
    {
        $this->tags = Tag::all();
    }
    private function createPostLog($action)
    {
        // Create a log for the post
        $postLogService = new PostLogsService();
        $postLogService->createLog($this->post->id, $action, auth()->id());
    }
    private function getAllStatuses()
    {
        $this->statuses = PostStatusEnum::cases(); // Get all status cases from the enum
    }
    public function showPublishModal()
    {
        $this->showPublishPostModal = true;
    }

    public function publishPost()
    {
        if (!$this->post) {
            return; // Ensure the post exists
        }

        $this->createPostLog("Post is Published by Admin Successfully.");
        $this->post->status = PostStatusEnum::PUBLISHED;
    
        // If the post is a featured post, set the `is_feature` flag to true
        if ($this->featurePost) {
            $this->post->is_feature = true;
        }else{
            $this->post->is_feature = false;
        }
        // Save the updated post
        $this->post->save();
        
        $this->showPublishPostModal = false;    
        return redirect()->route('home');
    }
    public function deleteFeaturedImage()
    {
        if ($this->post && $this->post->featured_image_path && Storage::disk('public')->exists($this->post->featured_image_path)) {
            Storage::disk('public')->delete($this->post->featured_image_path);
            $this->post->update(['featured_image_path' => null]);
            $this->featured_image = null;
        }
    }
    

    // Render method
    public function render()
    {
        return view('livewire.post.edit-post');
    }
}
