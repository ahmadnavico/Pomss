<?php

namespace App\Models;

use Attribute;
use App\Enums\PostStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $connection = 'mysql'; 
    // Fillable attributes for mass assignment
    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'content_file_path',
        'featured_image_path',
        'status',
        'user_id',
        'excerpt',
        'published_at',
        'reviewed_at',
        'plagiarism_detected',
        'is_feature',
        'thumbnail_image_path',
        'event_type',
        'event_for',
        'event_cost',
        'meeting_link',
        'venue',
        'entry_code',
    ];

    // Cast attributes to specific types
    protected $casts = [
        'published_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'status' => PostStatusEnum::class,
    ];

    // Define relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope for draft posts
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Scope for posts in review
    public function scopeInReview($query)
    {
        return $query->where('status', 'in review');
    }

    // Scope for rejected posts
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Scope for posts needing revision
    public function scopeRevision($query)
    {
        return $query->where('status', 'revision');
    }

    // Scope for pending posts
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for removed posts
    public function scopeRemoved($query)
    {
        return $query->where('status', 'removed');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_categories');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function content()
    {
        if ($this->content_file_path && Storage::disk('local')->exists($this->content_file_path)) {
            return json_decode(Storage::disk('local')->get($this->content_file_path))->content;
        }
        return '';
    }
    public function getLogs()
    {
        // Check if content file exists and the path is valid
        if ($this->content_file_path && Storage::disk('local')->exists($this->content_file_path)) {
            // Get the file content and decode the JSON
            $data = json_decode(Storage::disk('local')->get($this->content_file_path), true);
    
            // Check if logs exist in the content
            if (isset($data['logs'])) {
                // Loop through each log and fetch the user name using the user_id
                foreach ($data['logs'] as &$log) {
                    // Fetch the user by ID and add the user's name to the log
                    $user = User::find($log['user_id']);
                    $log['user_name'] = $user ? $user->name : 'Unknown User';  // Add user name or default to 'Unknown User'
                    $log = (object) $log; // Convert log to an object
                }
                return collect($data['logs']); // Return as a Laravel collection
            }
        }
        // Return empty collection if file doesn't exist or no logs are found
        return collect([]);
    }

    public function getFeaturedImage()
    {
        if ($this->featured_image_path) {
            return Storage::url($this->featured_image_path);
        }
        return 'https://images.unsplash.com/photo-1586232702178-f044c5f4d4b7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80';
    }
    public function getThumbnailImage()
    {
        // Check if the post has a thumbnail image
        if ($this->thumbnail_image_path) {
            return Storage::url($this->thumbnail_image_path);
        }

        // If no thumbnail exists, fall back to the featured image
        if ($this->featured_image_path) {
            return Storage::url($this->featured_image_path);
        }

        // If neither thumbnail nor featured image exists, return a default image
        return 'https://images.unsplash.com/photo-1586232702178-f044c5f4d4b7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80';
    }
}
