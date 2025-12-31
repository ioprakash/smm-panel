<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')->latest()->paginate(20);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('messages.user');
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate(['message' => 'required|string']);

        $ticket->messages()->create([
            'user_id' => auth()->id(), // Admin user ID
            'message' => $request->message,
            'is_admin' => true,
        ]);

        $ticket->update(['status' => 'answered']);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Ticket closed.');
    }
}
