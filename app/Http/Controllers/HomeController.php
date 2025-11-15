<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $packages = Package::where('status', 'active')->where('completion_status', 'completed')->get();

        return view('frontend.welcome', compact('packages'));
    }

    public function packages()
    {
        $packages = Package::where('status', 'active')->where('completion_status', 'completed')->get();
        return view('frontend.packages', compact('packages'));
    }
}
