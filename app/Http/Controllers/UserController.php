<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

    public function respond(){
        return view("user.respond");
    }

    public function maps($id){
        $results = DB::table("triger")->where("id",$id)->first();

        return view("user.maps",["result"=>$results]);
    }

    public function logout(){
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return view('login');
    }
}
