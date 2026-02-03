@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div x-data="{ activeTab: '{{ request()->get('tab', 'general') }}' }">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Manage application settings and configurations</p>
    </div>

    <!-- Modern Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" :class="activeTab === 'general' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    General
                </button>
                
                <button @click="activeTab = 'payment'" 
                        :class="activeTab === 'payment' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" :class="activeTab === 'payment' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Payment
                </button>
                
                <button @click="activeTab = 'shipping'" 
                        :class="activeTab === 'shipping' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" :class="activeTab === 'shipping' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                    </svg>
                    Shipping
                </button>
                
                <button @click="activeTab = 'email'" 
                        :class="activeTab === 'email' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" :class="activeTab === 'email' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Email
                </button>
                
                <button @click="activeTab = 'notifications'" 
                        :class="activeTab === 'notifications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" :class="activeTab === 'notifications' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    Notifications
                </button>
            </nav>
        </div>
    </div>
    <!-- General Settings Tab -->
    <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">General Settings</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.settings.general') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Site Name <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="site_name" 
                                   name="site_name" 
                                   value="{{ $settings['general']['site_name'] ?? 'JastipHype' }}" 
                                   required>
                        </div>
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email <span class="text-red-500">*</span></label>
                            <input type="email" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="contact_email" 
                                   name="contact_email" 
                                   value="{{ $settings['general']['contact_email'] ?? '' }}" 
                                   required>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                        <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                  id="site_description" 
                                  name="site_description" 
                                  rows="3">{{ $settings['general']['site_description'] ?? '' }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                            <input type="text" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="contact_phone" 
                                   name="contact_phone" 
                                   value="{{ $settings['general']['contact_phone'] ?? '' }}">
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="address" 
                                   name="address" 
                                   value="{{ $settings['general']['address'] ?? '' }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                            <input type="url" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="facebook_url" 
                                   name="facebook_url" 
                                   value="{{ $settings['general']['facebook_url'] ?? '' }}" 
                                   placeholder="https://facebook.com/...">
                        </div>
                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                            <input type="url" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="instagram_url" 
                                   name="instagram_url" 
                                   value="{{ $settings['general']['instagram_url'] ?? '' }}" 
                                   placeholder="https://instagram.com/...">
                        </div>
                        <div>
                            <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-2">Twitter URL</label>
                            <input type="url" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="twitter_url" 
                                   name="twitter_url" 
                                   value="{{ $settings['general']['twitter_url'] ?? '' }}" 
                                   placeholder="https://twitter.com/...">
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save General Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Payment Settings Tab -->
    <div x-show="activeTab === 'payment'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Payment Settings (Midtrans)</h2>
                <button type="button" 
                        onclick="testMidtrans()" 
                        class="inline-flex items-center px-4 py-2 border border-blue-600 rounded-lg text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Test Connection
                </button>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.settings.payment') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="midtrans_server_key" class="block text-sm font-medium text-gray-700 mb-2">Server Key <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="midtrans_server_key" 
                                   name="midtrans_server_key" 
                                   value="{{ $settings['payment']['midtrans_server_key'] ?? config('midtrans.server_key') }}" 
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Your Midtrans server key</p>
                        </div>
                        <div>
                            <label for="midtrans_client_key" class="block text-sm font-medium text-gray-700 mb-2">Client Key <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="midtrans_client_key" 
                                   name="midtrans_client_key" 
                                   value="{{ $settings['payment']['midtrans_client_key'] ?? config('midtrans.client_key') }}" 
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Your Midtrans client key</p>
                        </div>
                        <div>
                            <label for="midtrans_merchant_id" class="block text-sm font-medium text-gray-700 mb-2">Merchant ID</label>
                            <input type="text" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="midtrans_merchant_id" 
                                   name="midtrans_merchant_id" 
                                   value="{{ $settings['payment']['midtrans_merchant_id'] ?? config('midtrans.merchant_id') }}">
                            <p class="mt-1 text-sm text-gray-500">Your Midtrans merchant ID (optional)</p>
                        </div>
                        <div>
                            <label for="midtrans_environment" class="block text-sm font-medium text-gray-700 mb-2">Environment <span class="text-red-500">*</span></label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    id="midtrans_environment" 
                                    name="midtrans_environment" 
                                    required>
                                <option value="sandbox" {{ ($settings['payment']['midtrans_environment'] ?? config('midtrans.environment')) === 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                <option value="production" {{ ($settings['payment']['midtrans_environment'] ?? config('midtrans.environment')) === 'production' ? 'selected' : '' }}>Production (Live)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Payment Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Shipping Settings Tab -->
    <div x-show="activeTab === 'shipping'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Shipping Settings (RajaOngkir)</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.settings.shipping') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="rajaongkir_api_key" class="block text-sm font-medium text-gray-700 mb-2">RajaOngkir API Key <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="rajaongkir_api_key" 
                                   name="rajaongkir_api_key" 
                                   value="{{ $settings['shipping']['rajaongkir_api_key'] ?? config('rajaongkir.api_key') }}" 
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Your RajaOngkir API key</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="origin_city_id" class="block text-sm font-medium text-gray-700 mb-2">Origin City ID <span class="text-red-500">*</span></label>
                                <input type="number" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="origin_city_id" 
                                       name="origin_city_id" 
                                       value="{{ $settings['shipping']['origin_city_id'] ?? config('rajaongkir.origin_city') }}" 
                                       required>
                                <p class="mt-1 text-sm text-gray-500">RajaOngkir city ID for shipping origin</p>
                            </div>
                            <div>
                                <label for="origin_city_name" class="block text-sm font-medium text-gray-700 mb-2">Origin City Name <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="origin_city_name" 
                                       name="origin_city_name" 
                                       value="{{ $settings['shipping']['origin_city_name'] ?? '' }}" 
                                       required>
                                <p class="mt-1 text-sm text-gray-500">City name for display purposes</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Shipping Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Email Settings Tab -->
    <div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Email Settings (SMTP)</h2>
                <button type="button" 
                        onclick="document.getElementById('testEmailModal').classList.remove('hidden')" 
                        class="inline-flex items-center px-4 py-2 border border-blue-600 rounded-lg text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send Test Email
                </button>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.settings.email') }}">
                    @csrf
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="mail_mailer" class="block text-sm font-medium text-gray-700 mb-2">Mail Driver <span class="text-red-500">*</span></label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                        id="mail_mailer" 
                                        name="mail_mailer" 
                                        required>
                                    <option value="smtp" {{ ($settings['email']['mail_mailer'] ?? config('mail.default')) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ ($settings['email']['mail_mailer'] ?? config('mail.default')) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ ($settings['email']['mail_mailer'] ?? config('mail.default')) === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                </select>
                            </div>
                            <div>
                                <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-2">Mail Host <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="mail_host" 
                                       name="mail_host" 
                                       value="{{ $settings['email']['mail_host'] ?? config('mail.mailers.smtp.host') }}" 
                                       required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-2">Mail Port <span class="text-red-500">*</span></label>
                                <input type="number" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="mail_port" 
                                       name="mail_port" 
                                       value="{{ $settings['email']['mail_port'] ?? config('mail.mailers.smtp.port') }}" 
                                       required>
                            </div>
                            <div>
                                <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                        id="mail_encryption" 
                                        name="mail_encryption">
                                    <option value="">None</option>
                                    <option value="tls" {{ ($settings['email']['mail_encryption'] ?? config('mail.mailers.smtp.encryption')) === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($settings['email']['mail_encryption'] ?? config('mail.mailers.smtp.encryption')) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>
                            <div>
                                <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="mail_username" 
                                       name="mail_username" 
                                       value="{{ $settings['email']['mail_username'] ?? config('mail.mailers.smtp.username') }}" 
                                       required>
                            </div>
                        </div>
                        <div>
                            <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                            <input type="password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="mail_password" 
                                   name="mail_password" 
                                   value="{{ $settings['email']['mail_password'] ?? config('mail.mailers.smtp.password') }}" 
                                   required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-2">From Address <span class="text-red-500">*</span></label>
                                <input type="email" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="mail_from_address" 
                                       name="mail_from_address" 
                                       value="{{ $settings['email']['mail_from_address'] ?? config('mail.from.address') }}" 
                                       required>
                            </div>
                            <div>
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-2">From Name <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent focus:ring-2 focus:ring-blue-500" 
                                       id="mail_from_name" 
                                       name="mail_from_name" 
                                       value="{{ $settings['email']['mail_from_name'] ?? config('mail.from.name') }}" 
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Email Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Notifications Settings Tab -->
    <div x-show="activeTab === 'notifications'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Notification Settings</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.settings.notifications') }}">
                    @csrf
                    <p class="text-sm text-gray-600 mb-6">Enable or disable email notifications for specific events</p>
                    
                    <div class="space-y-4">
                        <label class="flex items-start">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" 
                                   id="notify_new_order" 
                                   name="notify_new_order" 
                                   {{ ($settings['notifications']['notify_new_order'] ?? true) ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">New Order</span>
                                <p class="text-sm text-gray-500">Notify when a new order is placed</p>
                            </div>
                        </label>

                        <label class="flex items-start">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" 
                                   id="notify_order_status" 
                                   name="notify_order_status" 
                                   {{ ($settings['notifications']['notify_order_status'] ?? true) ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Order Status Change</span>
                                <p class="text-sm text-gray-500">Notify customer when order status changes</p>
                            </div>
                        </label>

                        <label class="flex items-start">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" 
                                   id="notify_payment_received" 
                                   name="notify_payment_received" 
                                   {{ ($settings['notifications']['notify_payment_received'] ?? true) ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Payment Received</span>
                                <p class="text-sm text-gray-500">Notify when payment is confirmed</p>
                            </div>
                        </label>

                        <label class="flex items-start">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" 
                                   id="notify_new_customer" 
                                   name="notify_new_customer" 
                                   {{ ($settings['notifications']['notify_new_customer'] ?? false) ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">New Customer Registration</span>
                                <p class="text-sm text-gray-500">Notify when a new customer registers</p>
                            </div>
                        </label>

                        <label class="flex items-start">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" 
                                   id="notify_new_review" 
                                   name="notify_new_review" 
                                   {{ ($settings['notifications']['notify_new_review'] ?? true) ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">New Product Review</span>
                                <p class="text-sm text-gray-500">Notify when a customer submits a review</p>
                            </div>
                        </label>

                        <label class="flex items-start">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" 
                                   id="notify_low_stock" 
                                   name="notify_low_stock" 
                                   {{ ($settings['notifications']['notify_low_stock'] ?? true) ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Low Stock Alert</span>
                                <p class="text-sm text-gray-500">Notify when product stock is low</p>
                            </div>
                        </label>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Notification Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div id="testEmailModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Send Test Email</h3>
            <button onclick="document.getElementById('testEmailModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mb-4">
            <label for="test_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input type="email" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                   id="test_email" 
                   placeholder="Enter email address">
        </div>
        <div id="testEmailResult"></div>
        <div class="flex gap-3 mt-4">
            <button onclick="sendTestEmail()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Send Test Email
            </button>
            <button onclick="document.getElementById('testEmailModal').classList.add('hidden')" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Close
            </button>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    function sendTestEmail() {
        const email = document.getElementById('test_email').value;
        const resultDiv = document.getElementById('testEmailResult');
        
        if (!email) {
            resultDiv.innerHTML = '<div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">Please enter an email address</div>';
            return;
        }

        resultDiv.innerHTML = '<div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">Sending test email...</div>';

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
                resultDiv.innerHTML = '<div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">' + data.message + '</div>';
            } else {
                resultDiv.innerHTML = '<div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">' + data.message + '</div>';
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">Error: ' + error.message + '</div>';
        });
    }

    function testMidtrans() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Testing...';

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
