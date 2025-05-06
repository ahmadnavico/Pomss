<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class CreatePostController extends Controller
{
    public function __invoke(?Post $post = null)
    {
        //Check if user is authorized
        if ($post && auth()->user()->id !== $post->user_id) {
            abort(404, 'Not Found');
        }
        // Pass the post object to the view
        return view('admin.post.create', compact('post'));
    }
}
