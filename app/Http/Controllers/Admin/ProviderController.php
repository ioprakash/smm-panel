<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = \App\Models\SmmProvider::all();
        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        return view('admin.providers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255',
            'url' => 'required|url',
            'api_key' => 'required|string',
            'currency' => 'required|string|max:3',
        ]);

        // Auto balance check
        try {
            $api = new \App\Services\Smm\JapLikeProvider($validated['url'], $validated['api_key']);
            $validated['balance'] = $api->getBalance();
            $validated['is_active'] = true;
        } catch (\Exception $e) {
            $validated['balance'] = 0;
            // We still create it but maybe warn? For now just create.
        }

        \App\Models\SmmProvider::create($validated);

        return redirect()->route('admin.providers.index')->with('success', 'Provider added successfully.');
    }

    public function edit(\App\Models\SmmProvider $provider)
    {
        return view('admin.providers.edit', compact('provider'));
    }

    public function update(Request $request, \App\Models\SmmProvider $provider)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255',
            'url' => 'required|url',
            'api_key' => 'required|string',
            'currency' => 'required|string|max:3',
            'is_active' => 'boolean'
        ]);

        if ($request->filled('api_key')) {
             try {
                $api = new \App\Services\Smm\JapLikeProvider($validated['url'], $validated['api_key']);
                $validated['balance'] = $api->getBalance();
            } catch (\Exception $e) {
                // Keep old balance if fail
            }
        }

        $provider->update($validated);

        return redirect()->route('admin.providers.index')->with('success', 'Provider updated successfully.');
    }

    public function destroy(\App\Models\SmmProvider $provider)
    {
        $provider->delete();
        return redirect()->route('admin.providers.index')->with('success', 'Provider deleted successfully.');
    }
}
