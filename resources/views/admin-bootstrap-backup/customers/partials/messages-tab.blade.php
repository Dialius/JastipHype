<div class="tab-pane fade" id="messages">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3">Message History</h5>
            
            <!-- Message Thread -->
            <div class="card border-0 bg-light mb-3" style="max-height: 400px; overflow-y: auto;">
                <div class="card-body" id="messageThread">
                    <div class="text-center py-4">
                        <i class="bi bi-chat-dots fs-1 text-muted"></i>
                        <p class="text-muted mb-0">No messages yet</p>
                    </div>
                </div>
            </div>

            <!-- Send Message Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form id="sendMessageForm" action="{{ route('admin.messages.send', $customer->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="form-label">Send Message</label>
                            <textarea class="form-control" 
                                      id="message" 
                                      name="message" 
                                      rows="3" 
                                      required
                                      placeholder="Type your message here..."></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="send_email" 
                                   name="send_email" 
                                   value="1" 
                                   checked>
                            <label class="form-check-label" for="send_email">
                                Send email notification to customer
                            </label>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load messages when tab is shown
    const messagesTab = document.querySelector('a[href="#messages"]');
    if (messagesTab) {
        messagesTab.addEventListener('shown.bs.tab', function() {
            loadMessages();
        });
    }

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
                <div class="text-center py-4">
                    <i class="bi bi-chat-dots fs-1 text-muted"></i>
                    <p class="text-muted mb-0">No messages yet</p>
                </div>
            `;
            return;
        }

        let html = '';
        messages.forEach(message => {
            const isAdmin = message.admin_id !== null;
            const alignClass = isAdmin ? 'text-end' : 'text-start';
            const bgClass = isAdmin ? 'bg-primary text-white' : 'bg-white';
            const time = new Date(message.created_at).toLocaleString();

            html += `
                <div class="mb-3 ${alignClass}">
                    <div class="d-inline-block ${bgClass} rounded p-3" style="max-width: 70%;">
                        <p class="mb-1">${escapeHtml(message.message)}</p>
                        <small class="opacity-75">${time}</small>
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
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

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
            submitBtn.innerHTML = '<i class="bi bi-send"></i> Send Message';
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showToast(message, type) {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>
