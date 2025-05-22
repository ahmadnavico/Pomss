<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        return view('members.my-events');
    }
    public function showAll(){
        return view('members.view-events');
    }
    public function eventPayment(Post $post)
    {
        return view('event.event-payment', compact('post'));
    }

}
