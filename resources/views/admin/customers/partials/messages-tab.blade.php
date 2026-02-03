<div x-show="activeTab === 'messages'" x-transition x-cloak>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Message History</h3>
    
    <!-- Message Thread -->
    <div class="bg-gray-50 rounded-lg border border-gray-200 mb-4 p-4" style="max-height: 400px; overflow-y: auto;" id="messageThread">
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-500">No messages yet</p>
        </div>
    </div>

    <!-- Send Message Form -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <form id="sendMessageForm" action="{{ route('admin.messages.send', $customer->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Send Message</label>
                <textarea id="message" 
                          name="message" 
                          rows="3" 
                          required
                          placeholder="Type your message here..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex items-center mb-4">
                <input type="checkbox" 
                       id="send_email" 
                       name="send_email" 
                       value="1" 
                       checked
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="send_email" class="ml-2 block text-sm text-gray-700">
                    Send email notification to customer
                </label>
            </div>
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load messages when messages tab is clicked
    const messagesButtons = document.querySelectorAll('button[\\@click*="messages"]');
    messagesButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            setTimeout(loadMessages, 100);
        });
    });

    // Handle form submission
    const form = document.getElementById('sendMessageForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });
    }

    function loadMessages() {
        const customerId = {{ $customer->id }};
        fetch(`/admin/messages/${customerId}`)
            .then(response => response.json())
            .then(data => {
                displayMessages(data.messages);
            })
            .catch(error => {
                console.error('Error loading messages:', error);
            });
    }

    function displayMessages(messages) {
        const threadDiv = document.getElementById('messageThread');
        
        if (messages.length === 0) {
            threadDiv.innerHTML = `
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No messages yet</p>
                </div>
            `;
            return;
        }

        let html = '';
        messages.forEach(message => {
            const isAdmin = message.admin_id !== null;
            const alignClass = isAdmin ? 'justify-end' : 'justify-start';
            const bgClass = isAdmin ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200';
            const textClass = isAdmin ? 'text-white' : 'text-gray-900';
            const time = new Date(message.created_at).toLocaleString();

            html += `
                <div class="flex ${alignClass} mb-3">
                    <div class="${bgClass} rounded-lg p-3 max-w-[70%]">
                        <p class="text-sm ${textClass} mb-1">${escapeHtml(message.message)}</p>
                        <small class="text-xs opacity-75">${time}</small>
                    </div>
                </div>
            `;
        });

        threadDiv.innerHTML = html;
        threadDiv.scrollTop = threadDiv.scrollHeight;
    }

    function sendMessage() {
        const form = document.getElementById('sendMessageForm');
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>Sending...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.reset();
                loadMessages();
                showToast('Message sent successfully!', 'success');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            showToast('Failed to send message', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>Send Message';
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>
