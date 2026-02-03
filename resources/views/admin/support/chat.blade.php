@extends('admin.layouts.app')

@section('title', 'Live Chat')

@section('page-title', 'Live Chat')

@section('content')
<div x-data="adminLiveChat({{ json_encode($tickets) }})" x-init="init()" class="h-[calc(100vh-12rem)]">
    <div class="flex h-full rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
        {{-- Sidebar - Ticket List --}}
        <div class="w-80 border-r border-gray-200 dark:border-gray-800 flex flex-col">
            <div class="p-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="font-semibold text-gray-800 dark:text-white/90">Active Tickets</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    <span x-text="tickets.length"></span> open conversations
                </p>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <template x-if="tickets.length === 0">
                    <div class="p-4 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-3">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No active tickets</p>
                    </div>
                </template>
                
                <template x-for="ticket in tickets" :key="ticket.id">
                    <button 
                        @click="selectTicket(ticket)"
                        :class="selectedTicket?.id === ticket.id ? 'bg-blue-50 border-l-4 border-l-blue-600 dark:bg-blue-500/10' : 'hover:bg-gray-50 dark:hover:bg-gray-800/50'"
                        class="w-full p-4 text-left border-b border-gray-100 dark:border-gray-800 transition"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400" x-text="ticket.ticket_number"></span>
                                    <template x-if="ticket.unread_count > 0">
                                        <span class="flex h-5 min-w-[20px] items-center justify-center rounded-full bg-red-500 px-1.5 text-xs font-medium text-white" x-text="ticket.unread_count"></span>
                                    </template>
                                </div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90 truncate mt-1" x-text="ticket.user?.name || ticket.guest_name || 'Unknown'"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="ticket.subject"></p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <span 
                                    :class="{
                                        'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400': ticket.status === 'open',
                                        'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400': ticket.status === 'pending',
                                        'bg-purple-50 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400': ticket.status === 'in_progress'
                                    }"
                                    class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium capitalize"
                                    x-text="ticket.status.replace('_', ' ')"
                                ></span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2" x-text="formatTime(ticket.latest_message?.created_at || ticket.created_at)"></p>
                    </button>
                </template>
            </div>
        </div>

        {{-- Main Chat Area --}}
        <div class="flex-1 flex flex-col">
            <template x-if="!selectedTicket">
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Select a conversation</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Choose a ticket from the sidebar to start chatting</p>
                    </div>
                </div>
            </template>

            <template x-if="selectedTicket">
                <div class="flex-1 flex flex-col">
                    {{-- Chat Header --}}
                    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-900/50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                                <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white/90" x-text="selectedTicket.user?.name || selectedTicket.guest_name || 'Unknown'"></p>
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span x-text="selectedTicket.ticket_number"></span>
                                    <span>•</span>
                                    <span x-text="selectedTicket.subject"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <select 
                                x-model="selectedTicket.status"
                                @change="updateStatus()"
                                class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                            >
                                <option value="open">Open</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                            <a :href="'/admin/support/' + selectedTicket.id" class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:hover:bg-gray-800" title="View full ticket">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Messages --}}
                    <div x-ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4">
                        <template x-for="message in messages" :key="message.id">
                            <div :class="message.is_from_admin ? 'flex-row-reverse' : ''" class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <div :class="message.is_from_admin ? 'bg-blue-100 dark:bg-blue-500/15' : 'bg-gray-100 dark:bg-gray-800'" class="flex h-8 w-8 items-center justify-center rounded-full">
                                        <template x-if="message.is_from_admin">
                                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                            </svg>
                                        </template>
                                        <template x-if="!message.is_from_admin">
                                            <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                        </template>
                                    </div>
                                </div>
                                <div :class="message.is_from_admin ? 'text-right' : ''" class="flex-1 max-w-[80%]">
                                    <div :class="message.is_from_admin ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90'" class="inline-block rounded-2xl px-4 py-2.5 text-sm">
                                        <p class="whitespace-pre-wrap" x-text="message.message"></p>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1" x-text="formatTime(message.created_at)"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Input Area --}}
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                        <form @submit.prevent="sendMessage()" class="flex gap-2">
                            <textarea 
                                x-model="newMessage"
                                @keydown.enter.prevent="if (!$event.shiftKey) sendMessage()"
                                placeholder="Type your message... (Enter to send, Shift+Enter for new line)"
                                rows="1"
                                class="flex-1 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 resize-none"
                            ></textarea>
                            <button 
                                type="submit"
                                :disabled="!newMessage.trim() || isSending"
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-white transition hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function adminLiveChat(initialTickets) {
    return {
        tickets: initialTickets || [],
        selectedTicket: null,
        messages: [],
        newMessage: '',
        isSending: false,
        pollingInterval: null,
        lastMessageId: 0,

        init() {
            // Check for ticket param in URL
            const urlParams = new URLSearchParams(window.location.search);
            const ticketId = urlParams.get('ticket');
            if (ticketId) {
                const ticket = this.tickets.find(t => t.id == ticketId);
                if (ticket) {
                    this.selectTicket(ticket);
                }
            }

            // Start polling for new messages
            this.startPolling();
        },

        async selectTicket(ticket) {
            this.selectedTicket = ticket;
            await this.loadMessages();
            this.scrollToBottom();
        },

        async loadMessages() {
            if (!this.selectedTicket) return;

            try {
                const response = await fetch(`/admin/support/${this.selectedTicket.id}/messages`);
                const data = await response.json();
                this.messages = data.messages || [];
                this.lastMessageId = this.messages.length > 0 ? this.messages[this.messages.length - 1].id : 0;
                this.scrollToBottom();
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.isSending || !this.selectedTicket) return;

            const message = this.newMessage;
            this.newMessage = '';
            this.isSending = true;

            try {
                const response = await fetch(`/admin/support/${this.selectedTicket.id}/reply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message, is_internal_note: false })
                });

                const data = await response.json();
                if (data.success) {
                    this.messages.push(data.message);
                    this.lastMessageId = data.message.id;
                    this.scrollToBottom();
                }
            } catch (error) {
                console.error('Error sending message:', error);
            } finally {
                this.isSending = false;
            }
        },

        async updateStatus() {
            if (!this.selectedTicket) return;

            try {
                await fetch(`/admin/support/${this.selectedTicket.id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: this.selectedTicket.status })
                });
            } catch (error) {
                console.error('Error updating status:', error);
            }
        },

        async pollMessages() {
            if (!this.selectedTicket) return;

            try {
                const response = await fetch(`/admin/support/${this.selectedTicket.id}/messages?after_id=${this.lastMessageId}`);
                const data = await response.json();

                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        if (!this.messages.find(m => m.id === msg.id)) {
                            this.messages.push(msg);
                        }
                    });
                    this.lastMessageId = data.messages[data.messages.length - 1].id;
                    this.scrollToBottom();
                }
            } catch (error) {
                console.error('Error polling messages:', error);
            }
        },

        startPolling() {
            this.pollingInterval = setInterval(() => this.pollMessages(), 5000);
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        formatTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            
            if (days === 0) {
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            } else if (days < 7) {
                return date.toLocaleDateString('id-ID', { weekday: 'short' }) + ' ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            } else {
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            }
        }
    }
}
</script>
@endsection
