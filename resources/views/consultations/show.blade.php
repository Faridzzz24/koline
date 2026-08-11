@extends('layouts.app')
@section('title', 'Detail Konsultasi Medis')

@push('styles')
<style>
html, body {
    height: 100vh;
    overflow: hidden !important;
}
.main-content {
    height: 100vh;
    overflow: hidden !important;
    display: flex;
    flex-direction: column;
    padding-bottom: 1.5rem !important;
}
.chat-messages {
    scroll-behavior: smooth !important;
}
@keyframes chatBubblePop {
    0% { opacity: 0; transform: translateY(8px) scale(0.98); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
.chat-bubble-wrapper {
    animation: chatBubblePop 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    will-change: transform, opacity;
}
.chat-timestamp { font-size: 0.65rem; color: var(--txt-muted); }
.consultation-meta-item {
    background: var(--bg-surface);
    padding: 0.35rem 0.75rem;
    border-radius: var(--r-md);
    border: 1px solid var(--bdr-subtle);
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    white-space: nowrap;
    font-size: 0.775rem;
    color: var(--txt-muted);
}
@media (max-width: 1024px) {
    html, body { overflow: auto !important; height: auto; }
    .main-content { overflow: visible !important; height: auto; padding-bottom: 5rem !important; }
    .consultation-grid { grid-template-columns: 1fr !important; }
    .chat-card-container {
        height: min(72vh, 600px) !important;
        min-height: 480px !important;
        max-height: 650px !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
    }
    .chat-messages {
        flex: 1 1 0% !important;
        height: 0 !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        overscroll-behavior: contain !important;
    }
}
@media (max-width: 768px) {
    .consultation-header-bar {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 0.75rem !important;
    }
    .consultation-meta-wrapper {
        width: 100% !important;
        justify-content: flex-start !important;
        gap: 0.4rem !important;
    }
    .ai-chatbot-trigger {
        display: none !important;
    }
}
</style>
@endpush

@section('content')
{{-- Main Header Bar --}}
<div class="consultation-header-bar" style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--bdr-subtle); flex-shrink: 0; flex-wrap: wrap;">
    <div>
        <a href="{{ route('consultations.index') }}" class="btn btn-ghost btn-sm mb-2" style="color: var(--clr-brand-light); font-weight: 600; padding-left: 0; display: inline-flex; width: auto;">
            ← Kembali ke Daftar Sesi
        </a>
        <h1 style="font-size: clamp(1.35rem, 3.5vw, 1.65rem); font-weight: 800; color: var(--txt-heading); margin: 0 0 0.15rem; letter-spacing: -0.01em;">
            Detail Sesi Telekonsultasi
        </h1>
        <div style="font-size: 0.8rem; color: var(--txt-muted);">
            ID Sesi: <strong style="color: var(--clr-brand-light); font-family: monospace;">#KOL-{{ str_pad($consultation->id, 5, '0', STR_PAD_LEFT) }}</strong>
        </div>
    </div>

    {{-- Right Side: Inline Session Metadata & Status Badge --}}
    <div class="consultation-meta-wrapper" style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; justify-content: flex-end;" x-data="{ currentClock: '' }" x-init="setInterval(() => { const d = new Date(); currentClock = String(d.getHours()).padStart(2,'0')+':'+String(d.getMinutes()).padStart(2,'0')+':'+String(d.getSeconds()).padStart(2,'0')+' WIB'; }, 1000); const d = new Date(); currentClock = String(d.getHours()).padStart(2,'0')+':'+String(d.getMinutes()).padStart(2,'0')+':'+String(d.getSeconds()).padStart(2,'0')+' WIB';">
        <div class="consultation-meta-item">
            <svg width="13" height="13" fill="none" stroke="#38BDF8" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 16 14"/></svg>
            <span x-text="currentClock" style="color: #38BDF8; font-weight: 700;">--:--:-- WIB</span>
        </div>
        <div class="consultation-meta-item">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <strong style="color: var(--txt-heading);">{{ $consultation->consultation_date->format('d M Y') }}</strong>
        </div>
        <div class="consultation-meta-item">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 16 14"/></svg>
            <strong style="color: var(--txt-heading);">{{ substr($consultation->consultation_time, 0, 5) }} WIB</strong>
        </div>
        <div class="consultation-meta-item">
            <strong style="color: var(--clr-brand-light);">⏱️ {{ $consultation->duration_hours ?? 1 }} Jam</strong>
        </div>
        <div class="consultation-meta-item">
            <strong style="color: var(--clr-brand-light);">Rp {{ number_format($consultation->fee, 0, ',', '.') }}</strong>
        </div>
        <span class="badge badge-{{ $consultation->status_color }}" style="font-size: 0.8rem; padding: 0.4rem 0.875rem; border-radius: var(--r-full); font-weight: 700; white-space: nowrap;">
            ● {{ $consultation->status_label }}
        </span>
    </div>
</div>

@php
    $hasRightSidebar = auth()->user()->isDoctor() || $consultation->diagnosis || ($consultation->status === 'pending' && auth()->user()->isPatient());
@endphp

{{-- Workstation Grid --}}
<div class="consultation-grid" style="display: grid; grid-template-columns: {{ $hasRightSidebar ? '1fr 360px' : '1fr' }}; gap: 1.5rem; align-items: stretch; flex: 1; min-height: 0; height: 100%;">

    {{-- Left Area: Live Teleconsultation Chat Room --}}
    <div style="height: 100%; min-height: 0;">
        <div class="card chat-card-container" style="padding: 0; overflow: hidden; border-radius: var(--r-xl); box-shadow: 0 10px 30px rgba(0,0,0,0.25); height: 100%; min-height: 480px; display: flex; flex-direction: column;" x-data="liveChatApp({{ $consultation->id }}, {{ auth()->id() }})" x-init="initChat()">
            
            {{-- Chat Room Top Header --}}
            <div style="padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--bdr-subtle); display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; background: var(--bg-surface); flex-shrink: 0; flex-wrap: wrap;">
                @php
                    $targetUser = auth()->user()->isPatient() ? $consultation->doctor->user : $consultation->patient;
                    $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $targetUser->name);
                    $words = explode(' ', trim($cleanName));
                    $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                @endphp
                <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                    <div class="initial-avatar initial-avatar-sm" style="width: 42px; height: 42px; min-width: 42px; font-size: 0.95rem; flex-shrink: 0;">
                        {{ $initials }}
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 700; color: var(--txt-heading); font-size: 0.975rem; line-height: 1.3; word-break: break-word;">
                            @if(auth()->user()->isPatient())
                                {{ $consultation->doctor->user->name }}
                            @else
                                {{ $consultation->patient->name }}
                            @endif
                        </div>
                        <div style="font-size: 0.775rem; color: var(--clr-teal-light); font-weight: 600; margin-top: 0.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            @if(auth()->user()->isPatient()){{ $consultation->doctor->specialization->name }}@else Akun Pasien Terverifikasi @endif
                        </div>
                    </div>
                </div>

                {{-- Dynamic Timer Status Pill --}}
                <div>
                    <template x-if="!activeStarted">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #F59E0B; background: rgba(245, 158, 11, 0.12); padding: 0.35rem 0.85rem; border-radius: var(--r-full); border: 1px solid rgba(245, 158, 11, 0.3); display: inline-flex; align-items: center; gap: 0.4rem;">
                            🔒 Belum Dimulai (<span x-text="startTimeFormatted">08:00 WIB</span>)
                        </span>
                    </template>

                    <template x-if="activeStarted && !activeExpired">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #38BDF8; background: rgba(56, 189, 248, 0.12); padding: 0.35rem 0.85rem; border-radius: var(--r-full); border: 1px solid rgba(56, 189, 248, 0.3); display: inline-flex; align-items: center; gap: 0.4rem;"
                              :style="sessionTimer < 300 ? 'color: #EF4444; background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.4);' : ''">
                            ⏱️ Sisa Waktu: <strong x-text="formatTime(sessionTimer)" style="font-family: monospace; font-size: 0.85rem; margin-left: 0.1rem;">00:00</strong>
                        </span>
                    </template>

                    <template x-if="activeExpired">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #EF4444; background: rgba(239, 68, 68, 0.12); padding: 0.35rem 0.85rem; border-radius: var(--r-full); border: 1px solid rgba(239, 68, 68, 0.3); display: inline-flex; align-items: center; gap: 0.4rem;">
                            ⛔ Waktu Sesi Berakhir
                        </span>
                    </template>
                </div>
            </div>

            {{-- Scrollable Chat Messages Area --}}
            <div x-ref="chatBox" class="chat-messages" style="flex: 1 1 0%; min-height: 0; max-height: 100%; padding: 1.25rem 1.5rem; overflow-y: auto !important; display: flex; flex-direction: column; gap: 0.75rem;">
                
                {{-- Session Start Badge --}}
                <div style="text-align: center; margin-bottom: 0.25rem;">
                    <span class="badge badge-muted" style="font-size: 0.75rem; padding: 0.35rem 0.85rem;">
                        Konsultasi Dimulai · {{ $consultation->consultation_date->format('d M Y') }}
                    </span>
                </div>

                {{-- Pending Doctor Approval Banner --}}
                <div x-show="consultationStatus === 'pending'" x-transition>
                    @if(auth()->user()->isDoctor())
                        <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: var(--r-lg); padding: 1.25rem; text-align: center; color: #FBBF24; margin-bottom: 0.5rem;">
                            <div style="font-weight: 800; font-size: 1.05rem; margin-bottom: 0.35rem;">⚠️ Permintaan Konsultasi Masuk</div>
                            <div style="font-size: 0.875rem; color: var(--txt-body); line-height: 1.6; max-width: 520px; margin: 0 auto 1rem;">
                                Pasien <strong>{{ $consultation->patient->name }}</strong> mengajukan janji konsultasi pada <strong>{{ $consultation->consultation_date->format('d M Y') }}</strong> jam <strong>{{ substr($consultation->consultation_time, 0, 5) }} WIB</strong>. Setujui permintaan ini untuk membuka ruang chat medis.
                            </div>
                            <form action="{{ route('consultations.confirm', $consultation) }}" method="POST" style="margin: 0; display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="font-weight: 700; padding: 0.65rem 1.75rem;">
                                    ✓ Setujui & Terima Sesi Konsultasi
                                </button>
                            </form>
                        </div>
                    @else
                        <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: var(--r-lg); padding: 1.25rem; text-align: center; color: #FBBF24; margin-bottom: 0.5rem;">
                            <div style="font-weight: 800; font-size: 1.05rem; margin-bottom: 0.35rem;">🔒 Menunggu Persetujuan Dokter</div>
                            <div style="font-size: 0.875rem; color: var(--txt-body); line-height: 1.6; max-width: 520px; margin: 0 auto;">
                                Janji konsultasi Anda telah berhasil dikirim ke <strong>{{ $consultation->doctor->user->name }}</strong>.<br>
                                Fitur chat medis akan otomatis terbuka segera setelah Dokter menyetujui sesi konsultasi ini.
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Patient Primary Complaint Card --}}
                <div style="background: rgba(2, 132, 199, 0.08); border-radius: var(--r-lg); padding: 1.25rem; border: 1px solid rgba(2, 132, 199, 0.22);">
                    <div style="font-size: 0.75rem; color: var(--clr-brand-light); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Keluhan Medis Utama Pasien:
                    </div>
                    <p style="color: var(--txt-heading); font-size: 0.95rem; margin: 0; line-height: 1.7; font-weight: 500;">
                        {{ $consultation->complaint }}
                    </p>
                </div>

                {{-- Live Dynamic Messages --}}
                <template x-for="msg in messages" :key="msg.id">
                    <div class="chat-bubble-wrapper" :class="msg.is_sent ? 'sent' : 'received'">
                        <div class="chat-bubble" :class="msg.is_sent ? 'sent' : 'received'" x-text="msg.message"></div>
                        <div class="chat-meta" :class="msg.is_sent ? 'sent' : ''" x-text="msg.sender_name + ' · ' + msg.created_at"></div>
                    </div>
                </template>

                <div x-show="messages.length === 0" style="text-align: center; color: var(--txt-muted); padding: 2.5rem 1rem;">
                    <div style="font-weight: 600; color: var(--txt-body); font-size: 0.95rem; margin-bottom: 0.25rem;">Belum Ada Pesan Sesi</div>
                    <div style="font-size: 0.825rem;">Ketik pesan di bawah untuk memulai diskusi medis secara real-time.</div>
                </div>
            </div>

            {{-- Bottom Chat Input Bar --}}
            <template x-if="['confirmed', 'active'].includes(consultationStatus) && activeStarted && !activeExpired">
                <form @submit.prevent="sendMessage()" class="chat-input-bar" style="flex-shrink: 0;">
                    <input type="text" 
                           x-model="newMessage" 
                           class="chat-input" 
                           placeholder="Ketik pesan konsultasi medis di sini..." 
                           :disabled="isSubmitting" 
                           autocomplete="off" 
                           required>
                    <button type="submit" 
                            class="chat-send-btn" 
                            :disabled="isSubmitting || !newMessage.trim()" 
                            aria-label="Kirim Pesan" 
                            title="Kirim Pesan (Tekan Enter)">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
            </template>

            <template x-if="consultationStatus === 'pending'">
                <div style="padding: 1.125rem 1.5rem; text-align: center; background: rgba(245, 158, 11, 0.08); color: #FBBF24; font-size: 0.875rem; border-top: 1px solid var(--bdr-subtle); font-weight: 600;">
                    🔒 Menunggu persetujuan dokter. Ruang chat medis akan otomatis terbuka di sini begitu Dokter menyetujui janji konsultasi.
                </div>
            </template>

            <template x-if="['completed', 'cancelled'].includes(consultationStatus) || activeExpired">
                <div style="padding: 1.125rem 1.5rem; text-align: center; background: var(--bg-surface); color: var(--txt-muted); font-size: 0.875rem; border-top: 1px solid var(--bdr-subtle); font-weight: 500;">
                    Sesi konsultasi <span x-text="consultationStatus === 'completed' ? 'telah resmi diselesaikan' : (consultationStatus === 'cancelled' ? 'telah dibatalkan' : 'telah berakhir')"></span>.
                </div>
            </template>
        </div>
    </div>

    {{-- Right Sidebar: Medical Action Workstation (Shown only when action is needed) --}}
    @if($hasRightSidebar)
    <div style="display: flex; flex-direction: column; gap: 1.5rem; height: 100%;">

        {{-- Doctor Workstation Action: Confirm --}}
        @if(auth()->user()->isDoctor() && $consultation->status === 'pending')
            <div class="card card-sm" style="border: 1px solid rgba(245, 158, 11, 0.4); background: rgba(245, 158, 11, 0.05); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h4 style="margin-bottom: 0.75rem; color: #FBBF24;">Konfirmasi Antrean Janji</h4>
                    <p style="font-size: 0.85rem; color: var(--txt-body); line-height: 1.6; margin-bottom: 1.25rem;">
                        Pasien ini telah memesan sesi konsultasi. Klik konfirmasi untuk membuka ruang percakapan medis.
                    </p>
                </div>
                <form action="{{ route('consultations.confirm', $consultation) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="background: linear-gradient(135deg, #0284C7 0%, #0D9488 100%);">
                        Konfirmasi Sesi Konsultasi
                    </button>
                </form>
            </div>
        @endif

        {{-- Doctor Workstation Action: Complete & Fill Medical Record --}}
        @if(auth()->user()->isDoctor() && in_array($consultation->status, ['confirmed', 'active']))
            <div class="card card-sm" style="height: 100%; display: flex; flex-direction: column;">
                <h3 style="margin-bottom: 1rem; font-size: 1.05rem; font-weight: 700; color: var(--txt-heading); border-bottom: 1px solid var(--bdr-subtle); padding-bottom: 0.75rem;">
                    Diagnosis & Selesaikan Sesi
                </h3>
                <form action="{{ route('consultations.complete', $consultation) }}" method="POST" style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 0.875rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700; font-size: 0.825rem;">Diagnosis Medis *</label>
                            <textarea name="diagnosis" class="form-input" rows="2" placeholder="Tuliskan hasil diagnosis medis pasien..." required style="font-size: 0.875rem;"></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700; font-size: 0.825rem;">Resep Obat (Opsional)</label>
                            <textarea name="prescription" class="form-input" rows="2" placeholder="Nama obat, aturan pakai, & dosis..." style="font-size: 0.875rem;"></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700; font-size: 0.825rem;">Catatan / Anjuran Dokter</label>
                            <textarea name="notes" class="form-input" rows="2" placeholder="Anjuran istirahat atau instruksi pendukung..." style="font-size: 0.875rem;"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 1rem;">
                        Selesaikan Konsultasi Medis
                    </button>
                </form>
            </div>
        @endif

        {{-- Completed Medical Record Display --}}
        @if($consultation->diagnosis)
            <div class="card card-sm" style="border: 1px solid rgba(16, 185, 129, 0.3); background: rgba(16, 185, 129, 0.04);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <svg width="18" height="18" fill="none" stroke="#34D399" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #34D399; margin: 0;">Resume Rekam Medis</h3>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.75rem; color: var(--txt-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 0.25rem;">Diagnosis Dokter:</div>
                    <p style="font-size: 0.9rem; color: var(--txt-heading); line-height: 1.65; margin: 0; font-weight: 500;">
                        {{ $consultation->diagnosis }}
                    </p>
                </div>

                @if($consultation->prescription)
                    <div style="background: rgba(13, 148, 136, 0.12); border-radius: var(--r-md); padding: 0.875rem 1rem; border: 1px solid rgba(13, 148, 136, 0.3);">
                        <div style="font-size: 0.725rem; color: var(--clr-teal-light); font-weight: 700; text-transform: uppercase; margin-bottom: 0.25rem;">Resep Obat Resmi:</div>
                        <p style="font-size: 0.875rem; color: var(--txt-heading); margin: 0; line-height: 1.6; font-weight: 600;">
                            {{ $consultation->prescription }}
                        </p>
                    </div>
                @endif

                @if($consultation->notes)
                    <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--txt-muted);">
                        <strong>Catatan:</strong> {{ $consultation->notes }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Patient Action: Cancel --}}
        @if($consultation->status === 'pending' && auth()->user()->isPatient())
            <form action="{{ route('consultations.cancel', $consultation) }}" method="POST" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin membatalkan sesi konsultasi ini?')">
                @csrf
                <button type="submit" class="btn btn-outline btn-block btn-sm" style="color: var(--clr-danger); border-color: rgba(239, 68, 68, 0.3);">
                    Batalkan Janji Konsultasi
                </button>
            </form>
        @endif

    </div>
    @endif
</div>

@push('scripts')
<script>
function liveChatApp(consultationId, currentUserId) {
    return {
        messages: @json($initialMessages),
        newMessage: '',
        isSubmitting: false,
        consultationStatus: '{{ $consultation->status }}',

        startTimestampMs: {{ $consultation->start_date_time->timestamp * 1000 }},
        endTimestampMs: {{ $consultation->end_date_time->timestamp * 1000 }},
        
        startTimer: 0,
        sessionTimer: 0,
        activeStarted: {{ in_array($consultation->status, ['confirmed', 'active']) ? 'true' : 'false' }},
        activeExpired: {{ in_array($consultation->status, ['completed', 'cancelled']) ? 'true' : 'false' }},

        startTimeFormatted: '{{ substr($consultation->consultation_time, 0, 5) }} WIB',
        durationHours: {{ $consultation->duration_hours ?? 1 }},
        timerInterval: null,

        initChat() {
            this.$nextTick(() => this.scrollToBottom());
            
            // Immediate initial time sync
            this.syncTimers();

            // Real-time ticking interval every 1 second based on Date.now()
            this.timerInterval = setInterval(() => {
                this.syncTimers();
            }, 1000);

            // Auto background polling for new messages & status sync every 1.5 seconds
            setInterval(() => {
                this.fetchMessages();
            }, 1500);
        },

        syncTimers() {
            const now = Date.now();
            const isConfirmedOrActive = ['confirmed', 'active'].includes(this.consultationStatus);
            const isCompletedOrCancelled = ['completed', 'cancelled'].includes(this.consultationStatus);

            if (isCompletedOrCancelled) {
                this.activeStarted = true;
                this.activeExpired = true;
                this.sessionTimer = 0;
                return;
            }

            if (!isConfirmedOrActive) {
                this.activeStarted = false;
                this.activeExpired = false;
                this.sessionTimer = 0;
                return;
            }

            if (now >= this.endTimestampMs) {
                this.activeStarted = true;
                this.activeExpired = true;
                this.sessionTimer = 0;
            } else {
                this.activeStarted = true;
                this.activeExpired = false;
                this.sessionTimer = Math.max(0, Math.floor((this.endTimestampMs - now) / 1000));
            }
        },

        formatTime(seconds) {
            if (seconds <= 0) return '00:00';
            const hrs = Math.floor(seconds / 3600);
            const mins = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            if (hrs > 0) {
                return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }
            return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        },

        isNearBottom() {
            const box = this.$refs.chatBox;
            if (!box) return true;
            return (box.scrollHeight - box.scrollTop - box.clientHeight) < 150;
        },

        async fetchMessages() {
            try {
                const res = await fetch(`/konsultasi/${consultationId}/pesan`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    
                    const serverStatus = data.status || this.consultationStatus;
                    const serverMessages = data.messages || data;

                    // Real-time Status Synchronization (< 1.5s delay, 0 page refresh needed!)
                    if (serverStatus && serverStatus !== this.consultationStatus) {
                        this.consultationStatus = serverStatus;
                        this.syncTimers();
                    }

                    let hasNew = false;
                    for (const sMsg of serverMessages) {
                        const existing = this.messages.find(m => m.id === sMsg.id);
                        if (!existing) {
                            const tempIdx = this.messages.findIndex(m => m.is_pending && m.message === sMsg.message);
                            if (tempIdx !== -1) {
                                this.messages.splice(tempIdx, 1, sMsg);
                            } else {
                                this.messages.push(sMsg);
                            }
                            hasNew = true;
                        }
                    }

                    if (hasNew && this.isNearBottom()) {
                        this.$nextTick(() => this.scrollToBottom());
                    }
                }
            } catch (e) {
                console.error(e);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || !this.activeStarted || this.activeExpired) return;
            const text = this.newMessage.trim();
            this.newMessage = '';
            
            // 1. Optimistic Instant UI Insertion (0ms delay!)
            const tempId = 'temp_' + Date.now();
            const d = new Date();
            const clockStr = String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
            const currentUserName = '{{ auth()->user()->name }}';
            
            const optimisticMsg = {
                id: tempId,
                sender_id: currentUserId,
                sender_name: currentUserName,
                message: text,
                created_at: clockStr,
                is_sent: true,
                is_pending: true
            };

            this.messages.push(optimisticMsg);
            this.$nextTick(() => this.scrollToBottom());

            // 2. Background Sync HTTP POST
            try {
                const res = await fetch(`/konsultasi/${consultationId}/pesan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ message: text })
                });

                if (res.ok) {
                    const result = await res.json();
                    if (result.data) {
                        const tempIdx = this.messages.findIndex(m => m.id === tempId);
                        if (tempIdx !== -1) {
                            this.messages.splice(tempIdx, 1, result.data);
                        } else if (!this.messages.some(m => m.id === result.data.id)) {
                            this.messages.push(result.data);
                        }
                        this.$nextTick(() => this.scrollToBottom());
                    }
                }
            } catch (e) {
                console.error(e);
            }
        },

        scrollToBottom() {
            const box = this.$refs.chatBox;
            if (box) {
                box.scrollTo({
                    top: box.scrollHeight,
                    behavior: 'smooth'
                });
            }
        }
    };
}
</script>
@endpush
@endsection
