<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

class InstructorController extends Controller
{
    public function Dashboard()
    {
          $viewData = [
           'meta_title'=> 'Dashboard | LMS Dashboard',
           'meta_desc'=> 'Learning management system',
           'meta_image'=> url('pwa_assets/android-chrome-256x256.png'),

        ];

        return view('instructor.dashboard', $viewData);
    }
}