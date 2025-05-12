<?php

namespace App\Livewire\Post;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use Livewire\Component;

class AllPosts extends Component
{
    public function render()
    {
        $posts = Post::where('status', PostStatusEnum::PUBLISHED->value)->get();

        return view('livewire.post.all-posts', compact('posts'));
    }
}
