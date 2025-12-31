<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
        $services = \App\Models\Service::with('category')->where('is_active', true)->orderBy('price')->get();
        
        return view('services.index', compact('categories', 'services'));
    }
}
