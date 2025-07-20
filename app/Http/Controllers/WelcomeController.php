<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
  public function index()
    {
          $viewData = [
           'meta_title'=> 'home | LMS Dashboard',
           'meta_desc'=> 'Learning management system',
           'meta_image'=> url('pwa_assets/android-chrome-256x256.png'),

        ];

        return view('welcome', $viewData);
    }
}