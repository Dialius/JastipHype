<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Store a new support ticket
     */
    public function store(Request $request)
    {
        // Build validation rules based on auth status
        $rules = [
            'subject' => 'required|string|max:255',
            'category' => 'required|in:general,order,return,shipping,product,technical,other',
            'message' => 'required|string|min:1',
        ];

        // Only require guest fields if user is not authenticated
        if (!Auth::check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
        }

        $validated = $request->validate($rules);

        // Create ticket
        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'guest_name' => Auth::check() ? null : $validated['guest_name'],
            'guest_email' => Auth::check() ? null : $validated['guest_email'],
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'status' => 'open',
            'priority' => 'normal',
        ]);

        // Create initial message
        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_from_admin' => false,
        ]);

        // Store guest email in session for future API calls
        if (!Auth::check() && !empty($validated['guest_email'])) {
            $request->session()->put('support_guest_email', $validated['guest_email']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'ticket' => $ticket->fresh(['messages']),
                'message' => 'Ticket created successfully. Ticket number: ' . $ticket->ticket_number,
            ]);
        }

        return redirect()->back()->with('success', 'Support ticket created! Ticket number: ' . $ticket->ticket_number);
    }

    /**
     * Get user's tickets
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['latestMessage'])
            ->orderBy('last_reply_at', 'desc');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            // For guests, we need session-based ticket lookup
            $guestEmail = $request->session()->get('support_guest_email');
            if ($guestEmail) {
                $query->where('guest_email', $guestEmail);
            } else {
                $query->whereRaw('1 = 0'); // No tickets for unknown guests
            }
        }

        $tickets = $query->get();

        if ($request->wantsJson()) {
            return response()->json([
                'tickets' => $tickets,
            ]);
        }

        return view('support.index', compact('tickets'));
    }

    /**
     * Show a specific ticket
     */
    public function show(Request $request, SupportTicket $ticket)
    {
        // Authorization check
        if (Auth::check()) {
            if ($ticket->user_id !== Auth::id()) {
                abort(403);
            }
        } else {
            $guestEmail = $request->session()->get('support_guest_email');
            if ($ticket->guest_email !== $guestEmail) {
                abort(403);
            }
        }

        $ticket->load(['messages.user', 'messages.admin']);

        // Mark customer's unread messages as read (admin messages)
        $ticket->messages()->where('is_from_admin', true)->whereNull('read_at')->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json([
                'ticket' => $ticket,
            ]);
        }

        return view('support.show', compact('ticket'));
    }

    /**
     * Send a message in a ticket
     */
    public function sendMessage(Request $request, SupportTicket $ticket)
    {
        // Authorization check
        if (Auth::check()) {
            if ($ticket->user_id !== Auth::id()) {
                abort(403);
            }
        } else {
            $guestEmail = $request->session()->get('support_guest_email');
            if ($ticket->guest_email !== $guestEmail) {
                abort(403);
            }
        }

        $validated = $request->validate([
            'message' => 'required|string|min:1',
        ]);

        $message = SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_from_admin' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->fresh(['user']),
            ]);
        }

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    /**
     * Get messages for a ticket (polling endpoint)
     */
    public function getMessages(Request $request, SupportTicket $ticket)
    {
        // Authorization check
        if (Auth::check()) {
            if ($ticket->user_id !== Auth::id()) {
                abort(403);
            }
        } else {
            $guestEmail = $request->session()->get('support_guest_email');
            if ($ticket->guest_email !== $guestEmail) {
                abort(403);
            }
        }

        $afterId = $request->get('after_id', 0);
        
        $messages = $ticket->messages()
            ->with(['user', 'admin'])
            ->where('id', '>', $afterId)
            ->orderBy('id', 'asc')
            ->get();

        // Mark admin messages as read
        $ticket->messages()
            ->where('is_from_admin', true)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $messages,
            'ticket_status' => $ticket->status,
        ]);
    }

    /**
     * Get active ticket for widget
     */
    public function getActiveTicket(Request $request)
    {
        $ticket = null;

        if (Auth::check()) {
            $ticket = SupportTicket::with(['messages.user', 'messages.admin'])
                ->where('user_id', Auth::id())
                ->open()
                ->latest()
                ->first();
        } else {
            $guestEmail = $request->session()->get('support_guest_email');
            if ($guestEmail) {
                $ticket = SupportTicket::with(['messages.user', 'messages.admin'])
                    ->where('guest_email', $guestEmail)
                    ->open()
                    ->latest()
                    ->first();
            }
        }

        return response()->json([
            'ticket' => $ticket,
            'has_ticket' => !is_null($ticket),
        ]);
    }
}
