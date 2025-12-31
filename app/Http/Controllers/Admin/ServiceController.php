<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::orderBy('sort_order')->get();
        $services = \App\Models\Service::with('category')->latest()->paginate(20);
        return view('admin.services.index', compact('categories', 'services'));
    }
}
