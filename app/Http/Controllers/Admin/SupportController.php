<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display list of support tickets
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $priority = $request->get('priority');
        $search = $request->get('search');

        $query = SupportTicket::with(['user', 'latestMessage', 'assignedAdmin'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('is_from_admin', false)->whereNull('read_at');
            }]);

        // Status filter
        if ($status !== 'all') {
            if ($status === 'pending') {
                $query->whereIn('status', ['open', 'pending']);
            } elseif ($status === 'solved') {
                $query->whereIn('status', ['resolved', 'closed']);
            } else {
                $query->where('status', $status);
            }
        }

        // Priority filter
        if ($priority) {
            $query->where('priority', $priority);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('guest_name', 'like', "%{$search}%")
                    ->orWhere('guest_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->orderByRaw("CASE 
            WHEN status = 'open' THEN 1 
            WHEN status = 'pending' THEN 2 
            WHEN status = 'in_progress' THEN 3 
            WHEN status = 'resolved' THEN 4 
            ELSE 5 END")
            ->orderBy('priority', 'desc')
            ->orderBy('last_reply_at', 'desc')
            ->paginate(20);

        // Stats
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::whereIn('status', ['open', 'pending', 'in_progress'])->count(),
            'solved' => SupportTicket::whereIn('status', ['resolved', 'closed'])->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->whereIn('status', ['open', 'pending'])->count(),
        ];

        return view('admin.support.index', compact('tickets', 'stats', 'status', 'priority', 'search'));
    }

    /**
     * Show single ticket
     */
    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'messages.user', 'messages.admin', 'assignedAdmin']);

        // Mark customer messages as read
        $ticket->messages()
            ->where('is_from_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.support.show', compact('ticket'));
    }

    /**
     * Reply to a ticket
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1',
            'is_internal_note' => 'boolean',
        ]);

        $message = SupportMessage::create([
            'ticket_id' => $ticket->id,
            'admin_id' => Auth::id(),
            'message' => $validated['message'],
            'is_from_admin' => true,
            'is_internal_note' => $validated['is_internal_note'] ?? false,
        ]);

        // Assign admin if not assigned
        if (!$ticket->assigned_admin_id) {
            $ticket->update(['assigned_admin_id' => Auth::id()]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->fresh(['admin']),
            ]);
        }

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,pending,in_progress,resolved,closed',
        ]);

        $ticket->update(['status' => $validated['status']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Ticket status updated.');
    }

    /**
     * Update ticket priority
     */
    public function updatePriority(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $ticket->update(['priority' => $validated['priority']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Priority updated successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Ticket priority updated.');
    }

    /**
     * Assign ticket to admin
     */
    public function assign(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
        ]);

        $ticket->update(['assigned_admin_id' => $validated['admin_id']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket assigned successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Ticket assigned.');
    }

    /**
     * Live chat interface
     */
    public function chat()
    {
        $tickets = SupportTicket::with(['user', 'latestMessage'])
            ->whereIn('status', ['open', 'pending', 'in_progress'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('is_from_admin', false)->whereNull('read_at');
            }])
            ->orderBy('last_reply_at', 'desc')
            ->get();

        return view('admin.support.chat', compact('tickets'));
    }

    /**
     * Get messages for polling (admin side)
     */
    public function getMessages(Request $request, SupportTicket $ticket)
    {
        $afterId = $request->get('after_id', 0);
        
        $messages = $ticket->messages()
            ->with(['user', 'admin'])
            ->where('id', '>', $afterId)
            ->orderBy('id', 'asc')
            ->get();

        // Mark customer messages as read
        $ticket->messages()
            ->where('is_from_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $messages,
            'ticket' => $ticket->fresh(),
        ]);
    }

    /**
     * Get unread count for notification badge
     */
    public function getUnreadCount()
    {
        $count = SupportMessage::where('is_from_admin', false)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
