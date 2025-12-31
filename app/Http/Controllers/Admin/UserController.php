<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\User::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role') && $request->role !== 'All Roles') {
            $query->where('role', strtolower($request->role));
        }

        if ($request->filled('status') && $request->status !== 'All Status') {
            if ($request->status === 'Banned') {
                $query->where('is_banned', true);
            } elseif ($request->status === 'Active') {
                $query->where('is_banned', false);
            }
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function loginAsUser(\App\Models\User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot login as another admin.');
        }
        
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function toggleBan(\App\Models\User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot ban an admin.');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'banned' : 'activated';
        return back()->with('success', "User has been $status.");
    }

    public function edit(\App\Models\User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, \App\Models\User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'balance' => 'numeric|min:0'
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

}
