@extends('admin.layouts.app')

@section('title', 'Ticket: ' . $ticket->ticket_number)

@section('page-title', 'Support Ticket')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.support.index') }}" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:hover:bg-gray-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ $ticket->ticket_number }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $ticket->subject }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.support.chat') }}?ticket={{ $ticket->id }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                </svg>
                Open in Chat
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main Content - Messages --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Messages --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-800 dark:text-white/90">Conversation</h3>
                </div>
                
                <div class="max-h-[500px] overflow-y-auto p-6 space-y-4" id="messagesContainer">
                    @foreach($ticket->messages as $message)
                    <div class="flex gap-3 {{ $message->is_from_admin ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $message->is_from_admin ? 'bg-blue-100 dark:bg-blue-500/15' : 'bg-gray-100 dark:bg-gray-800' }}">
                                @if($message->is_from_admin)
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                </svg>
                                @else
                                <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 {{ $message->is_from_admin ? 'text-right' : '' }}">
                            <div class="flex items-center gap-2 {{ $message->is_from_admin ? 'justify-end' : '' }}">
                                <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $message->is_from_admin ? ($message->admin?->name ?? 'Support Team') : $message->sender_name }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $message->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                            <div class="mt-2 inline-block max-w-[90%] rounded-2xl px-4 py-3 {{ $message->is_from_admin ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90' }}">
                                <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                            </div>
                            @if($message->is_internal_note)
                            <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">
                                <svg class="inline h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                Internal note - not visible to customer
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Reply Form --}}
                <div class="border-t border-gray-100 p-6 dark:border-gray-800">
                    <form action="{{ route('admin.support.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <textarea name="message" rows="3" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" placeholder="Type your reply..." required></textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <input type="checkbox" name="is_internal_note" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    Internal note (not visible to customer)
                                </label>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                    </svg>
                                    Send Reply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar - Ticket Details --}}
        <div class="space-y-6">
            {{-- Customer Info --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 font-semibold text-gray-800 dark:text-white/90">Customer</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                            <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white/90">{{ $ticket->customer_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->customer_email }}</p>
                        </div>
                    </div>
                    @if($ticket->user)
                    <a href="{{ route('admin.customers.show', $ticket->user_id) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        View Profile
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                    @else
                    <span class="inline-flex items-center gap-1 text-sm text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Guest User
                    </span>
                    @endif
                </div>
            </div>

            {{-- Ticket Details --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 font-semibold text-gray-800 dark:text-white/90">Ticket Details</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                        <p class="font-medium text-gray-800 capitalize dark:text-white/90">{{ $ticket->category }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Created</p>
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Last Reply</p>
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : 'No reply yet' }}</p>
                    </div>
                </div>
            </div>

            {{-- Status & Priority --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 font-semibold text-gray-800 dark:text-white/90">Status & Priority</h3>
                
                <form action="{{ route('admin.support.update-status', $ticket) }}" method="POST" class="mb-4">
                    @csrf
                    <label class="mb-2 block text-sm text-gray-500 dark:text-gray-400">Status</label>
                    <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </form>
                
                <form action="{{ route('admin.support.update-priority', $ticket) }}" method="POST">
                    @csrf
                    <label class="mb-2 block text-sm text-gray-500 dark:text-gray-400">Priority</label>
                    <select name="priority" onchange="this.form.submit()" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="normal" {{ $ticket->priority === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </form>
            </div>

            {{-- Assigned Admin --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 font-semibold text-gray-800 dark:text-white/90">Assigned To</h3>
                @if($ticket->assignedAdmin)
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-500/15">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $ticket->assignedAdmin->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->assignedAdmin->email }}</p>
                    </div>
                </div>
                @else
                <p class="text-sm text-gray-500 dark:text-gray-400">Not assigned yet</p>
                <form action="{{ route('admin.support.assign', $ticket) }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="admin_id" value="{{ auth()->id() }}">
                    <button type="submit" class="w-full rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Assign to Me
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
});
</script>
@endsection
