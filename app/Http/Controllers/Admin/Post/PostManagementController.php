<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostManagementController extends Controller
{
    public function all()
    {
        return view('admin.post.showAll');
    }

    public function edit(Post $post)
    {
        return view('admin.post.edit', compact('post'));
    }
}
