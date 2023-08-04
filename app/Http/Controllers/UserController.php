<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function home()
    {
        return view('home');
    }

    function scheme()
    {
        return view('resource.scheme');
    }
    
    function company()
    {
        return view('resource.company');
    }
    
    function companyprofile()
    {
        return view('resource.companyprofile');
    }
}
