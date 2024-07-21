<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user(){
        return view("user.user");
    }

    public function devices(){
        return view("user.devices");
    }

    public function alarm(){
        return view("user.alarm");
    }
}
