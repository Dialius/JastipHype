{{-- Live Chat Widget --}}
<div 
    x-data="liveChatWidget()"
    x-init="init()"
    class="fixed bottom-6 right-6 z-50"
>
    {{-- Chat Button --}}
    <button 
        @click="toggleChat()"
        :class="[
            isOpen ? 'scale-0 opacity-0' : 'scale-100 opacity-100',
            isDarkBackground ? 'ring-2 ring-accent-gold ring-offset-2 ring-offset-gray-900' : ''
        ]"
        class="w-14 h-14 bg-black text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        
        {{-- Unread Badge --}}
        <span 
            x-show="unreadCount > 0"
            x-text="unreadCount"
            class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold"
        ></span>
        
        {{-- Pulse Animation --}}
        <span :class="isDarkBackground ? 'bg-accent-gold' : 'bg-black'" class="absolute w-14 h-14 rounded-full opacity-30 animate-ping"></span>
    </button>

    {{-- Chat Window --}}
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
        class="absolute bottom-0 right-0 w-[380px] max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="bg-gradient-to-r from-gray-900 to-black text-white p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold">JastipHype Support</h3>
                        <p class="text-xs text-gray-300 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            We typically reply in a few minutes
                        </p>
                    </div>
                </div>
                <button @click="toggleChat()" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Content --}}
        <div class="h-[400px] flex flex-col">
            <template x-if="!hasTicket && view === 'form'">
                {{-- New Ticket Form --}}
                <div class="flex-1 p-4 overflow-y-auto custom-scrollbar">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-accent-gold/10 rounded-full mb-3">
                            <svg class="w-6 h-6 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">Start a Conversation</h4>
                        <p class="text-sm text-gray-500 mt-1">We're here to help!</p>
                    </div>

                    <form @submit.prevent="createTicket()" class="space-y-3">
                        @guest
                        <div>
                            <input 
                                type="text" 
                                x-model="form.guest_name"
                                placeholder="Your Name"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-black focus:border-transparent"
                                required
                            >
                        </div>
                        <div>
                            <input 
                                type="email" 
                                x-model="form.guest_email"
                                placeholder="Your Email"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-black focus:border-transparent"
                                required
                            >
                        </div>
                        @endguest

                        <div>
                            <select 
                                x-model="form.category"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-black focus:border-transparent"
                                required
                            >
                                <option value="">Select topic</option>
                                <option value="order">Order Inquiry</option>
                                <option value="return">Return Request</option>
                                <option value="shipping">Shipping Question</option>
                                <option value="product">Product Information</option>
                                <option value="general">General Question</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <input 
                                type="text" 
                                x-model="form.subject"
                                placeholder="Subject"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-black focus:border-transparent"
                                required
                            >
                        </div>

                        <div>
                            <textarea 
                                x-model="form.message"
                                placeholder="How can we help you?"
                                rows="3"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-black focus:border-transparent resize-none"
                                required
                            ></textarea>
                        </div>

                        <button 
                            type="submit"
                            :disabled="isLoading"
                            class="w-full bg-black text-white py-2.5 rounded-lg font-medium hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <template x-if="isLoading">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="isLoading ? 'Sending...' : 'Start Chat'"></span>
                        </button>
                    </form>
                </div>
            </template>

            <template x-if="hasTicket || view === 'chat'">
                {{-- Chat Messages --}}
                <div class="flex-1 flex flex-col">
                    {{-- Ticket Info Bar --}}
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm flex items-center justify-between">
                        <span class="text-gray-600">Ticket: <strong x-text="ticket?.ticket_number"></strong></span>
                        <span 
                            :class="{
                                'text-blue-600': ticket?.status === 'open',
                                'text-yellow-600': ticket?.status === 'pending',
                                'text-purple-600': ticket?.status === 'in_progress',
                                'text-green-600': ticket?.status === 'resolved',
                                'text-gray-600': ticket?.status === 'closed'
                            }"
                            class="text-xs font-medium capitalize"
                            x-text="ticket?.status?.replace('_', ' ')"
                        ></span>
                    </div>

                    {{-- Messages Container --}}
                    <div 
                        x-ref="messagesContainer"
                        class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar"
                    >
                        <template x-for="message in messages" :key="message.id">
                            <div 
                                :class="message.is_from_admin ? 'justify-start' : 'justify-end'"
                                class="flex"
                            >
                                <div 
                                    :class="message.is_from_admin ? 'bg-gray-100 text-gray-900' : 'bg-black text-white'"
                                    class="max-w-[80%] px-4 py-2.5 rounded-2xl text-sm"
                                >
                                    <template x-if="message.is_from_admin">
                                        <p class="text-xs text-gray-500 font-medium mb-1" x-text="message.sender_name || 'Support Team'"></p>
                                    </template>
                                    <p x-text="message.message"></p>
                                    <p 
                                        :class="message.is_from_admin ? 'text-gray-400' : 'text-gray-300'"
                                        class="text-[10px] mt-1"
                                        x-text="formatTime(message.created_at)"
                                    ></p>
                                </div>
                            </div>
                        </template>

                        {{-- Typing Indicator --}}
                        <div x-show="isTyping" class="flex justify-start">
                            <div class="bg-gray-100 px-4 py-2.5 rounded-2xl">
                                <div class="flex gap-1">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>
                                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Message Input --}}
                    <div class="p-3 border-t">
                        <form @submit.prevent="sendMessage()" class="flex gap-2">
                            <input 
                                type="text"
                                x-model="newMessage"
                                placeholder="Type your message..."
                                class="flex-1 px-4 py-2 border border-gray-200 rounded-full text-sm focus:ring-2 focus:ring-black focus:border-transparent"
                                :disabled="ticket?.status === 'closed'"
                            >
                            <button 
                                type="submit"
                                :disabled="!newMessage.trim() || isSending || ticket?.status === 'closed'"
                                class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
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
function liveChatWidget() {
    return {
        isOpen: false,
        hasTicket: false,
        view: 'form', // 'form' or 'chat'
        ticket: null,
        messages: [],
        newMessage: '',
        unreadCount: 0,
        isLoading: false,
        isSending: false,
        isTyping: false,
        isDarkBackground: false,
        pollingInterval: null,
        lastMessageId: 0,
        form: {
            guest_name: '',
            guest_email: '',
            category: '',
            subject: '',
            message: ''
        },

        init() {
            // Check for active ticket on load
            this.checkActiveTicket();
            // Check background color on load and scroll
            this.checkBackgroundColor();
            window.addEventListener('scroll', () => this.checkBackgroundColor());
        },

        checkBackgroundColor() {
            const viewportHeight = window.innerHeight;
            const buttonY = viewportHeight - 50; // Button is at bottom-6 (~24px + 56px button height / 2)
            const scrollY = window.pageYOffset;
            
            const footer = document.querySelector('footer');
            const darkSections = document.querySelectorAll('[class*="bg-black"], [class*="bg-gray-900"], [class*="bg-gray-800"]');
            
            this.isDarkBackground = false;
            
            // Check if button overlaps with footer
            if (footer) {
                const footerRect = footer.getBoundingClientRect();
                if (footerRect.top < viewportHeight && footerRect.bottom > buttonY) {
                    this.isDarkBackground = true;
                    return;
                }
            }
            
            // Check other dark sections
            darkSections.forEach(section => {
                const rect = section.getBoundingClientRect();
                if (rect.top < buttonY && rect.bottom > buttonY - 56) {
                    this.isDarkBackground = true;
                }
            });
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            
            if (this.isOpen && this.hasTicket) {
                this.startPolling();
                this.scrollToBottom();
            } else {
                this.stopPolling();
            }
        },

        async checkActiveTicket() {
            try {
                const response = await fetch('/support/tickets/active');
                const data = await response.json();
                
                if (data.has_ticket) {
                    this.ticket = data.ticket;
                    this.messages = data.ticket.messages || [];
                    this.hasTicket = true;
                    this.view = 'chat';
                    this.lastMessageId = this.messages.length > 0 ? this.messages[this.messages.length - 1].id : 0;
                }
            } catch (error) {
                console.error('Error checking active ticket:', error);
            }
        },

        async createTicket() {
            this.isLoading = true;
            
            try {
                const response = await fetch('/support/tickets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.ticket = data.ticket;
                    this.messages = data.ticket.messages || [];
                    this.hasTicket = true;
                    this.view = 'chat';
                    this.lastMessageId = this.messages.length > 0 ? this.messages[this.messages.length - 1].id : 0;
                    this.startPolling();
                    
                    // Store email for guest session
                    if (this.form.guest_email) {
                        sessionStorage.setItem('support_guest_email', this.form.guest_email);
                    }
                } else if (data.errors) {
                    // Handle validation errors
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    alert('Please fix the following errors:\n' + errorMessages);
                } else if (data.message) {
                    alert(data.message);
                } else {
                    alert('Error creating ticket. Please try again.');
                }
            } catch (error) {
                console.error('Error creating ticket:', error);
                alert('Error creating ticket. Please check your connection and try again.');
            } finally {
                this.isLoading = false;
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.isSending) return;

            const message = this.newMessage;
            this.newMessage = '';
            this.isSending = true;

            // Optimistically add message
            const tempId = Date.now();
            this.messages.push({
                id: tempId,
                message: message,
                is_from_admin: false,
                created_at: new Date().toISOString()
            });
            this.scrollToBottom();

            try {
                const response = await fetch(`/support/tickets/${this.ticket.id}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                if (data.success) {
                    // Replace temp message with real one
                    const index = this.messages.findIndex(m => m.id === tempId);
                    if (index !== -1) {
                        this.messages[index] = data.message;
                        this.lastMessageId = data.message.id;
                    }
                }
            } catch (error) {
                console.error('Error sending message:', error);
            } finally {
                this.isSending = false;
            }
        },

        async pollMessages() {
            if (!this.ticket) return;

            try {
                const response = await fetch(`/support/tickets/${this.ticket.id}/messages?after_id=${this.lastMessageId}`);
                const data = await response.json();

                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        if (!this.messages.find(m => m.id === msg.id)) {
                            this.messages.push(msg);
                            if (!this.isOpen && msg.is_from_admin) {
                                this.unreadCount++;
                            }
                        }
                    });
                    this.lastMessageId = data.messages[data.messages.length - 1].id;
                    this.scrollToBottom();
                }

                // Update ticket status
                if (data.ticket_status) {
                    this.ticket.status = data.ticket_status;
                }
            } catch (error) {
                console.error('Error polling messages:', error);
            }
        },

        startPolling() {
            this.stopPolling();
            this.pollingInterval = setInterval(() => this.pollMessages(), 5000);
        },

        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
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
            const date = new Date(dateString);
            return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>

<style>
/* Custom Scrollbar for Chat Widget */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(156, 163, 175, 0.8);
}
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

@keyframes bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-4px); }
}
</style>
