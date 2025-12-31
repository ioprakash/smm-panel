<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }
    
    public function store(Request $request)
    {
        // Placeholder for saving settings
        // In a real app, this would update a Settings table or config file
        return redirect()->back()->with('success', 'Settings saved successfully (UI Only)');
    }
}
