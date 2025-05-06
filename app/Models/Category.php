<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Import SoftDeletes

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'meta_title', 'meta_description'];
    
    public function posts()
    {
    return $this->belongsToMany(Post::class, 'post_categories');
    }
}
