@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Settings</h1>
        <p class="text-muted mb-0">Manage application settings and configurations</p>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                <i class="bi bi-gear me-2"></i>General
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                <i class="bi bi-credit-card me-2"></i>Payment
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">
                <i class="bi bi-truck me-2"></i>Shipping
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                <i class="bi bi-envelope me-2"></i>Email
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                <i class="bi bi-bell me-2"></i>Notifications
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="settingsTabContent">
        <!-- General Settings -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.general') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_name" class="form-label">Site Name *</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" 
                                       value="{{ $settings['general']['site_name'] ?? 'JastipHype' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Contact Email *</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                       value="{{ $settings['general']['contact_email'] ?? '' }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ $settings['general']['site_description'] ?? '' }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                       value="{{ $settings['general']['contact_phone'] ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       value="{{ $settings['general']['address'] ?? '' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="facebook_url" class="form-label">Facebook URL</label>
                                <input type="url" class="form-control" id="facebook_url" name="facebook_url" 
                                       value="{{ $settings['general']['facebook_url'] ?? '' }}" placeholder="https://facebook.com/...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="instagram_url" class="form-label">Instagram URL</label>
                                <input type="url" class="form-control" id="instagram_url" name="instagram_url" 
                                       value="{{ $settings['general']['instagram_url'] ?? '' }}" placeholder="https://instagram.com/...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="twitter_url" class="form-label">Twitter URL</label>
                                <input type="url" class="form-control" id="twitter_url" name="twitter_url" 
                                       value="{{ $settings['general']['twitter_url'] ?? '' }}" placeholder="https://twitter.com/...">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save General Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="tab-pane fade" id="payment" role="tabpanel">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Settings (Midtrans)</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="testMidtrans()">
                        <i class="bi bi-check-circle me-2"></i>Test Connection
                    </button>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.payment') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="midtrans_server_key" class="form-label">Server Key *</label>
                            <input type="text" class="form-control" id="midtrans_server_key" name="midtrans_server_key" 
                                   value="{{ $settings['payment']['midtrans_server_key'] ?? config('midtrans.server_key') }}" required>
                            <small class="text-muted">Your Midtrans server key</small>
                        </div>
                        <div class="mb-3">
                            <label for="midtrans_client_key" class="form-label">Client Key *</label>
                            <input type="text" class="form-control" id="midtrans_client_key" name="midtrans_client_key" 
                                   value="{{ $settings['payment']['midtrans_client_key'] ?? config('midtrans.client_key') }}" required>
                            <small class="text-muted">Your Midtrans client key</small>
                        </div>
                        <div class="mb-3">
                            <label for="midtrans_merchant_id" class="form-label">Merchant ID</label>
                            <input type="text" class="form-control" id="midtrans_merchant_id" name="midtrans_merchant_id" 
                                   value="{{ $settings['payment']['midtrans_merchant_id'] ?? config('midtrans.merchant_id') }}">
                            <small class="text-muted">Your Midtrans merchant ID (optional)</small>
                        </div>
                        <div class="mb-3">
                            <label for="midtrans_environment" class="form-label">Environment *</label>
                            <select class="form-select" id="midtrans_environment" name="midtrans_environment" required>
                                <option value="sandbox" {{ ($settings['payment']['midtrans_environment'] ?? config('midtrans.environment')) === 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                <option value="production" {{ ($settings['payment']['midtrans_environment'] ?? config('midtrans.environment')) === 'production' ? 'selected' : '' }}>Production (Live)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Payment Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div class="tab-pane fade" id="shipping" role="tabpanel">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Shipping Settings (RajaOngkir)</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.shipping') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="rajaongkir_api_key" class="form-label">RajaOngkir API Key *</label>
                            <input type="text" class="form-control" id="rajaongkir_api_key" name="rajaongkir_api_key" 
                                   value="{{ $settings['shipping']['rajaongkir_api_key'] ?? config('rajaongkir.api_key') }}" required>
                            <small class="text-muted">Your RajaOngkir API key</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="origin_city_id" class="form-label">Origin City ID *</label>
                                <input type="number" class="form-control" id="origin_city_id" name="origin_city_id" 
                                       value="{{ $settings['shipping']['origin_city_id'] ?? config('rajaongkir.origin_city') }}" required>
                                <small class="text-muted">RajaOngkir city ID for shipping origin</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="origin_city_name" class="form-label">Origin City Name *</label>
                                <input type="text" class="form-control" id="origin_city_name" name="origin_city_name" 
                                       value="{{ $settings['shipping']['origin_city_name'] ?? '' }}" required>
                                <small class="text-muted">City name for display purposes</small>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Shipping Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="tab-pane fade" id="email" role="tabpanel">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Email Settings (SMTP)</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                        <i class="bi bi-envelope-check me-2"></i>Send Test Email
                    </button>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.email') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_mailer" class="form-label">Mail Driver *</label>
                                <select class="form-select" id="mail_mailer" name="mail_mailer" required>
                                    <option value="smtp" {{ ($settings['email']['mail_mailer'] ?? config('mail.default')) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ ($settings['email']['mail_mailer'] ?? config('mail.default')) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ ($settings['email']['mail_mailer'] ?? config('mail.default')) === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_host" class="form-label">Mail Host *</label>
                                <input type="text" class="form-control" id="mail_host" name="mail_host" 
                                       value="{{ $settings['email']['mail_host'] ?? config('mail.mailers.smtp.host') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="mail_port" class="form-label">Mail Port *</label>
                                <input type="number" class="form-control" id="mail_port" name="mail_port" 
                                       value="{{ $settings['email']['mail_port'] ?? config('mail.mailers.smtp.port') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="mail_encryption" class="form-label">Encryption</label>
                                <select class="form-select" id="mail_encryption" name="mail_encryption">
                                    <option value="">None</option>
                                    <option value="tls" {{ ($settings['email']['mail_encryption'] ?? config('mail.mailers.smtp.encryption')) === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($settings['email']['mail_encryption'] ?? config('mail.mailers.smtp.encryption')) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="mail_username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="mail_username" name="mail_username" 
                                       value="{{ $settings['email']['mail_username'] ?? config('mail.mailers.smtp.username') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mail_password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="mail_password" name="mail_password" 
                                   value="{{ $settings['email']['mail_password'] ?? config('mail.mailers.smtp.password') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_from_address" class="form-label">From Address *</label>
                                <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" 
                                       value="{{ $settings['email']['mail_from_address'] ?? config('mail.from.address') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_from_name" class="form-label">From Name *</label>
                                <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" 
                                       value="{{ $settings['email']['mail_from_name'] ?? config('mail.from.name') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Email Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="tab-pane fade" id="notifications" role="tabpanel">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notification Settings</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.notifications') }}">
                        @csrf
                        <p class="text-muted mb-4">Enable or disable email notifications for specific events</p>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_new_order" name="notify_new_order" 
                                   {{ ($settings['notifications']['notify_new_order'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_new_order">
                                <strong>New Order</strong>
                                <br><small class="text-muted">Notify when a new order is placed</small>
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_order_status" name="notify_order_status" 
                                   {{ ($settings['notifications']['notify_order_status'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_order_status">
                                <strong>Order Status Change</strong>
                                <br><small class="text-muted">Notify customer when order status changes</small>
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_payment_received" name="notify_payment_received" 
                                   {{ ($settings['notifications']['notify_payment_received'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_payment_received">
                                <strong>Payment Received</strong>
                                <br><small class="text-muted">Notify when payment is confirmed</small>
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_new_customer" name="notify_new_customer" 
                                   {{ ($settings['notifications']['notify_new_customer'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_new_customer">
                                <strong>New Customer Registration</strong>
                                <br><small class="text-muted">Notify when a new customer registers</small>
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_new_review" name="notify_new_review" 
                                   {{ ($settings['notifications']['notify_new_review'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_new_review">
                                <strong>New Product Review</strong>
                                <br><small class="text-muted">Notify when a customer submits a review</small>
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_low_stock" name="notify_low_stock" 
                                   {{ ($settings['notifications']['notify_low_stock'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_low_stock">
                                <strong>Low Stock Alert</strong>
                                <br><small class="text-muted">Notify when product stock is low</small>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Notification Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="test_email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="test_email" placeholder="Enter email address">
                </div>
                <div id="testEmailResult"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sendTestEmail()">Send Test Email</button>
            </div>
        </div>
    </div>
</div>

<style>
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }
</style>

@push('scripts')
<script>
    function sendTestEmail() {
        const email = document.getElementById('test_email').value;
        const resultDiv = document.getElementById('testEmailResult');
        
        if (!email) {
            resultDiv.innerHTML = '<div class="alert alert-warning">Please enter an email address</div>';
            return;
        }

        resultDiv.innerHTML = '<div class="alert alert-info">Sending test email...</div>';

        fetch('{{ route("admin.settings.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ test_email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
        });
    }

    function testMidtrans() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Testing...';

        fetch('{{ route("admin.settings.test-midtrans") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ ' + data.message);
            } else {
                alert('✗ ' + data.message);
            }
        })
        .catch(error => {
            alert('✗ Error: ' + error.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
</script>
@endpush
@endsection
