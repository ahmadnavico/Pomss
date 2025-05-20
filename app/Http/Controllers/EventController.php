<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        return view('members.my-events');
    }
    public function showAll(){
        return view('members.view-events');
    }
}
