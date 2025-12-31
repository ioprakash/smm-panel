<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets()->latest()->paginate(10);
        return view('user.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('user.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = auth()->user()->tickets()->create([
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => false,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load('messages.user');
        return view('user.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['message' => 'required|string']);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => false,
        ]);

        $ticket->touch(); // Updated updated_at

        return back()->with('success', 'Reply sent.');
    }
}
