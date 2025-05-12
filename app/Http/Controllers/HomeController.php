<?php

namespace App\Http\Controllers;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request){
        return view('welcome');
    }
}
