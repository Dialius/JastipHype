@extends('layouts.app')

@section('title', 'Data Privacy Dashboard - JastipHype')

@section('content')
<style>
    /* ─── Hero ─── */
    .gdpr-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        position: relative;
        overflow: hidden;
    }
    .gdpr-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(212,168,67,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .gdpr-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -15%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(212,168,67,0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ─── Section title underline ─── */
    .gdpr-section-title {
        position: relative;
        display: inline-block;
    }
    .gdpr-section-title::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #d4a843, transparent);
        border-radius: 2px;
    }

    /* ─── Action card ─── */
    .gdpr-action-card {
        background: white;
        border: 1px solid #f3f4f6;
        border-radius: 1rem;
        transition: all 0.3s ease;
    }
    .gdpr-action-card:hover {
        border-color: #e5e7eb;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }

    /* ─── Rights card ─── */
    .gdpr-rights-card {
        background: white;
        border: 1px solid #f3f4f6;
        border-radius: 1rem;
        transition: all 0.3s ease;
    }
    .gdpr-rights-card:hover {
        border-color: #d4a843;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    /* ─── History item ─── */
    .gdpr-history-item {
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .gdpr-history-item:hover {
        border-color: #e5e7eb;
        background: #fafafa;
    }

    /* ─── Modal ─── */
    .gdpr-modal-overlay {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    .gdpr-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .gdpr-modal-content {
        transform: scale(0.95) translateY(10px);
        transition: all 0.3s ease;
    }
    .gdpr-modal-overlay.active .gdpr-modal-content {
        transform: scale(1) translateY(0);
    }

    /* ─── Alert ─── */
    .gdpr-alert {
        border-radius: 14px;
        animation: gdpr-alert-in 0.4s ease;
    }
    @keyframes gdpr-alert-in {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ─── Icon badge ─── */
    .gdpr-icon-badge {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>

<!-- ═══════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════ -->
<section class="gdpr-hero py-16 md:py-24">
    <div class="container mx-auto px-4 max-w-5xl relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm text-gray-400">
                <li><a href="/" class="hover:text-yellow-500 transition-colors">Home</a></li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                <li class="text-yellow-500">Data Privacy</li>
            </ol>
        </nav>

        <div class="flex items-start gap-5">
            <div class="hidden md:flex w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-600/20 to-yellow-500/10 items-center justify-center flex-shrink-0 border border-yellow-500/10">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Data Privacy Dashboard</h1>
                <p class="text-gray-300 text-base leading-relaxed max-w-2xl">
                    Manage your personal data, download your information, or request data deletion — all in one place.
                </p>
                <div class="flex items-center gap-3 mt-4">
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ auth()->user()->name }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ auth()->user()->email }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     MAIN CONTENT
     ═══════════════════════════════════════════════ -->
<div class="container mx-auto px-4 max-w-5xl -mt-8 relative z-20 pb-20">

    <!-- ─── Alerts ─── -->
    @if(session('success'))
        <div class="gdpr-alert flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-800 px-5 py-4 mb-6">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="gdpr-alert flex items-center gap-3 bg-red-50 border border-red-100 text-red-800 px-5 py-4 mb-6">
            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- ─── Export & Delete Grid ─── -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">

        <!-- ════ Export Card ════ -->
        <div class="gdpr-action-card p-6 md:p-7">
            <div class="flex items-start gap-4 mb-5">
                <div class="gdpr-icon-badge bg-teal-50">
                    <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Export Your Data</h2>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Download all your personal data in JSON format. The export file will be available for 7 days after processing.
                    </p>
                </div>
            </div>

            <form action="{{ route('gdpr.request-export') }}" method="POST" class="mb-5">
                @csrf
                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-semibold px-7 py-2.5 rounded-xl text-sm hover:from-yellow-500 hover:to-yellow-400 transition-all shadow-lg shadow-yellow-500/20 hover:shadow-yellow-500/30 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Request Data Export
                </button>
            </form>

            @if($exportRequests->count() > 0)
                <div class="border-t border-gray-100 pt-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Export History
                    </h3>
                    <div class="space-y-2.5">
                        @foreach($exportRequests as $request)
                            <div class="gdpr-history-item p-3.5">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-xs text-gray-500 font-medium">
                                            {{ $request->created_at->format('d M Y H:i') }}
                                        </span>
                                        <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                            @if($request->status === 'completed') bg-emerald-50 text-emerald-700
                                            @elseif($request->status === 'processing') bg-amber-50 text-amber-700
                                            @elseif($request->status === 'failed') bg-red-50 text-red-700
                                            @else bg-gray-100 text-gray-600
                                            @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                    @if($request->status === 'completed' && !$request->isExpired())
                                        <a href="{{ route('gdpr.download-export', $request->id) }}"
                                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-yellow-700 hover:text-yellow-600 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    @elseif($request->isExpired())
                                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            Expired
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- ════ Delete Card ════ -->
        <div class="gdpr-action-card p-6 md:p-7">
            <div class="flex items-start gap-4 mb-5">
                <div class="gdpr-icon-badge bg-red-50">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Delete Your Data</h2>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Request permanent deletion of all your personal data. This process is irreversible and cannot be undone.
                    </p>
                </div>
            </div>

            <button
                onclick="openDeleteModal()"
                class="w-full sm:w-auto bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold px-7 py-2.5 rounded-xl text-sm hover:from-red-500 hover:to-red-400 transition-all shadow-lg shadow-red-500/20 hover:shadow-red-500/30 flex items-center justify-center gap-2 mb-5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                Request Data Deletion
            </button>

            @if($deletionRequests->count() > 0)
                <div class="border-t border-gray-100 pt-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Deletion History
                    </h3>
                    <div class="space-y-2.5">
                        @foreach($deletionRequests as $request)
                            <div class="gdpr-history-item p-3.5">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-xs text-gray-500 font-medium">
                                            {{ $request->created_at->format('d M Y H:i') }}
                                        </span>
                                        <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                            @if($request->status === 'completed') bg-emerald-50 text-emerald-700
                                            @elseif($request->status === 'approved') bg-blue-50 text-blue-700
                                            @elseif($request->status === 'processing') bg-amber-50 text-amber-700
                                            @elseif($request->status === 'rejected') bg-red-50 text-red-700
                                            @else bg-gray-100 text-gray-600
                                            @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                    @if($request->reason)
                                        <p class="text-xs text-gray-400 italic pl-0.5">Reason: {{ $request->reason }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- ─── Privacy Rights ─── -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-900 gdpr-section-title mb-8">Your Privacy Rights</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Right to Access -->
            <div class="gdpr-rights-card p-5">
                <div class="flex items-start gap-4">
                    <div class="gdpr-icon-badge bg-teal-50">
                        <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 text-sm mb-1">Right to Access</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">You can access and download your personal data at any time through the export feature.</p>
                    </div>
                </div>
            </div>

            <!-- Right to Rectification -->
            <div class="gdpr-rights-card p-5">
                <div class="flex items-start gap-4">
                    <div class="gdpr-icon-badge bg-blue-50">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 text-sm mb-1">Right to Rectification</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">You can correct inaccurate data through your profile settings at any time.</p>
                    </div>
                </div>
            </div>

            <!-- Right to Erasure -->
            <div class="gdpr-rights-card p-5">
                <div class="flex items-start gap-4">
                    <div class="gdpr-icon-badge bg-red-50">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 text-sm mb-1">Right to Erasure</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">You can request permanent deletion of your personal data using the delete feature above.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── Related Links ─── -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-900 gdpr-section-title mb-6">Related Policies</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('gdpr.cookie-policy') }}" class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-yellow-400 hover:bg-yellow-50/30 transition-all group">
                <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0 group-hover:bg-yellow-100 transition-colors">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <span class="text-sm font-semibold text-gray-900">Cookie Policy</span>
                    <span class="block text-xs text-gray-400">Learn about cookie usage</span>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('gdpr.privacy-policy') }}" class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-yellow-400 hover:bg-yellow-50/30 transition-all group">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-100 transition-colors">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <span class="text-sm font-semibold text-gray-900">Privacy Policy</span>
                    <span class="block text-xs text-gray-400">How we handle your data</span>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <!-- ─── Contact CTA ─── -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 md:p-8 text-center">
        <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-white font-bold text-lg mb-2">Need Help with Your Data?</h2>
        <p class="text-gray-400 text-sm mb-4">If you have questions about your privacy rights or need assistance, we're here to help.</p>
        <a href="mailto:info@jastiphype.shop" class="inline-flex items-center gap-2 text-yellow-500 hover:text-yellow-400 text-sm font-medium transition-colors">
            <span>info@jastiphype.shop</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     DELETE CONFIRMATION MODAL
     ═══════════════════════════════════════════════ -->
<div id="deleteModal" class="gdpr-modal-overlay fixed inset-0 flex items-center justify-center z-50">
    <div class="gdpr-modal-content bg-white rounded-2xl p-6 md:p-8 max-w-md w-full mx-4 shadow-2xl">
        <!-- Warning Icon -->
        <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-5">
            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>

        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Confirm Data Deletion</h3>
        <p class="text-gray-500 text-sm text-center mb-5">
            This action will permanently delete all your personal data, including:
        </p>

        <div class="bg-red-50/50 rounded-xl p-4 mb-5 space-y-2">
            <div class="flex items-center gap-2.5 text-sm text-gray-700">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Profile information
            </div>
            <div class="flex items-center gap-2.5 text-sm text-gray-700">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Order history (will be anonymized)
            </div>
            <div class="flex items-center gap-2.5 text-sm text-gray-700">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reviews and wishlist
            </div>
            <div class="flex items-center gap-2.5 text-sm text-gray-700">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Shopping cart
            </div>
        </div>

        <div class="bg-red-50 rounded-xl p-3 flex items-center gap-2.5 mb-6">
            <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs text-red-700 font-semibold">This process cannot be undone!</p>
        </div>

        <form action="{{ route('gdpr.request-deletion') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason (optional)</label>
                <textarea
                    name="reason"
                    rows="3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500/30 focus:border-yellow-500 transition-all resize-none"
                    placeholder="Tell us why you want to delete your data..."
                ></textarea>
            </div>
            <div class="flex gap-3">
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="flex-1 bg-gray-100 text-gray-700 font-semibold px-5 py-2.5 rounded-xl text-sm hover:bg-gray-200 transition-all"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="flex-1 bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold px-5 py-2.5 rounded-xl text-sm hover:from-red-500 hover:to-red-400 transition-all shadow-lg shadow-red-500/20"
                >
                    Yes, Delete My Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeleteModal() {
    document.getElementById('deleteModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
@endsection
