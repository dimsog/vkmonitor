<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): View
    {
        if (Auth::guest()) {
            return view('home.guest');
        }
        return view('home.index');
    }
}
