<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Check if $categories is needed for layout (e.g. navbar services dropdown)
        // If the layout header relies on $categories variable being present
        $categories = Category::with(['services' => function($q) {
                $q->where('is_active', true)->limit(5); 
            }])->where('is_active', true)->orderBy('sort_order')->get();

        return view('blog.show', compact('post', 'categories'));
    }
}
