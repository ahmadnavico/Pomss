<?php

namespace App\Http\Controllers;

use App\Models\MemberChangeRequest;
use Illuminate\Http\Request;

class MemberChangeRequestController extends Controller
{
    public function index(){
        return view('admin.member.showAll');
    }
    public function edit($id)
    {
        return view('admin.member.edit', ['id' => $id]);
    }
}
