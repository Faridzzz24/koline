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
@media (max-width: 1024px) {
    html, body, .main-content { overflow: auto !important; height: auto; }
    .consultation-grid { grid-template-columns: 1fr !important; }
}
</style>
@endpush

@section('content')
{{-- Main Header Bar --}}
<div class="main-header" style="margin-bottom: 1rem; padding-bottom: 0.75rem; flex-shrink: 0;">
    <div>
        <a href="{{ route('consultations.index') }}" class="btn btn-ghost btn-sm mb-2" style="color: var(--clr-brand-light); font-weight: 600; padding-left: 0;">
            ← Kembali ke Daftar Sesi
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--txt-heading); margin: 0 0 0.15rem; letter-spacing: -0.01em;">
            Detail Sesi Telekonsultasi
        </h1>
        <div style="font-size: 0.8rem; color: var(--txt-muted);">
            ID Sesi: <strong style="color: var(--clr-brand-light); font-family: monospace;">#KOL-{{ str_pad($consultation->id, 5, '0', STR_PAD_LEFT) }}</strong>
        </div>
    </div>    {{-- Right Side: Inline Session Metadata & Status Badge --}}
    <div style="display: flex; align-items: center; gap: 0.875rem; flex-wrap: wrap; justify-content: flex-end;">
        <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.8rem; color: var(--txt-muted); background: var(--bg-surface); padding: 0.45rem 0.875rem; border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle);" x-data="{ currentClock: '' }" x-init="setInterval(() => { const d = new Date(); currentClock = String(d.getHours()).padStart(2,'0')+':'+String(d.getMinutes()).padStart(2,'0')+':'+String(d.getSeconds()).padStart(2,'0')+' WIB'; }, 1000); const d = new Date(); currentClock = String(d.getHours()).padStart(2,'0')+':'+String(d.getMinutes()).padStart(2,'0')+':'+String(d.getSeconds()).padStart(2,'0')+' WIB';">
            <span style="display: flex; align-items: center; gap: 0.35rem; color: #38BDF8; font-weight: 700;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span x-text="currentClock">--:--:-- WIB</span>
            </span>
            <span style="color: var(--bdr-subtle);">•</span>
            <span style="display: flex; align-items: center; gap: 0.35rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <strong style="color: var(--txt-heading);">{{ $consultation->consultation_date->format('d M Y') }}</strong>
            </span>
            <span style="color: var(--bdr-subtle);">•</span>
            <span style="display: flex; align-items: center; gap: 0.35rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <strong style="color: var(--txt-heading);">{{ substr($consultation->consultation_time, 0, 5) }} WIB</strong>
            </span>
            <span style="color: var(--bdr-subtle);">•</span>
            <span style="display: flex; align-items: center; gap: 0.35rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <strong style="color: var(--clr-brand-light);">⏱️ {{ $consultation->duration_hours ?? 1 }} Jam</strong>
            </span>
            <span style="color: var(--bdr-subtle);">•</span>
            <span style="display: flex; align-items: center; gap: 0.35rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <strong style="color: var(--clr-brand-light);">Rp {{ number_format($consultation->fee, 0, ',', '.') }}</strong>
            </span>
        </div>

        <span class="badge badge-{{ $consultation->status_color }}" style="font-size: 0.825rem; padding: 0.45rem 1rem; border-radius: var(--r-full); font-weight: 700;">
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
        <div class="card" style="padding: 0; overflow: hidden; border-radius: var(--r-xl); box-shadow: 0 10px 30px rgba(0,0,0,0.25); height: 100%; min-height: 480px; display: flex; flex-direction: column;" x-data="liveChatApp({{ $consultation->id }}, {{ auth()->id() }})" x-init="initChat()">
            
            {{-- Chat Room Top Header --}}
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--bdr-subtle); display: flex; align-items: center; justify-content: space-between; gap: 1rem; background: var(--bg-surface); flex-shrink: 0;">
                @php
                    $targetUser = auth()->user()->isPatient() ? $consultation->doctor->user : $consultation->patient;
                    $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $targetUser->name);
                    $words = explode(' ', trim($cleanName));
                    $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                @endphp
                <div style="display: flex; align-items: center; gap: 0.875rem;">
                    <div class="initial-avatar initial-avatar-sm" style="width: 40px; height: 40px; font-size: 0.95rem;">
                        {{ $initials }}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--txt-heading); font-size: 1rem; line-height: 1.2;">
                            @if(auth()->user()->isPatient())
                                {{ $consultation->doctor->user->name }}
                            @else
                                {{ $consultation->patient->name }}
                            @endif
                        </div>
                        <div style="font-size: 0.775rem; color: var(--clr-brand-light); font-weight: 600; margin-top: 0.1rem;">
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

                {{-- Banner if session not started yet --}}
                <template x-if="!activeStarted">
                    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--r-lg); padding: 1.125rem; text-align: center; color: #FBBF24;">
                        <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem;">🔒 Sesi Konsultasi Belum Dimulai</div>
                        <div style="font-size: 0.85rem; color: var(--txt-body); line-height: 1.6;">
                            Sesi medis ini dijadwalkan berlangsung pada pukul <strong style="color: #FBBF24;" x-text="startTimeFormatted">08:00 WIB</strong>.<br>
                            Ruang chat dan fitur pengiriman pesan akan otomatis terbuka tepat pada jam tersebut.
                        </div>
                        <div style="margin-top: 0.75rem; font-family: monospace; font-size: 1.05rem; font-weight: 800; color: #38BDF8; background: rgba(56, 189, 248, 0.1); padding: 0.5rem 1rem; border-radius: var(--r-md); display: inline-block;">
                            ⏳ Dimulai Dalam: <span x-text="formatTime(startTimer)">00:00:00</span>
                        </div>
                    </div>
                </template>

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
            @if(in_array($consultation->status, ['confirmed', 'active']))
                <template x-if="activeStarted && !activeExpired">
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

                <template x-if="!activeStarted">
                    <div style="padding: 1.125rem 1.5rem; text-align: center; background: var(--bg-surface); color: #FBBF24; font-size: 0.875rem; border-top: 1px solid var(--bdr-subtle); font-weight: 600;">
                        🔒 Sesi belum dimulai. Fitur obrolan akan terbuka otomatis pukul <span x-text="startTimeFormatted">08:00 WIB</span>.
                    </div>
                </template>

                <template x-if="activeExpired">
                    <div style="padding: 1.125rem 1.5rem; text-align: center; background: var(--bg-surface); color: var(--clr-danger); font-size: 0.875rem; border-top: 1px solid var(--bdr-subtle); font-weight: 600;">
                        ⛔ Waktu durasi sesi konsultasi telah berakhir.
                    </div>
                </template>
            @else
                <div style="padding: 1.125rem 1.5rem; text-align: center; background: var(--bg-surface); color: var(--txt-muted); font-size: 0.875rem; border-top: 1px solid var(--bdr-subtle); font-weight: 500;">
                    Sesi konsultasi {{ $consultation->status === 'completed' ? 'telah resmi diselesaikan' : ($consultation->status === 'cancelled' ? 'telah dibatalkan' : 'menunggu konfirmasi dokter') }}.
                </div>
            @endif
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

        startTimestampMs: {{ $consultation->start_date_time->timestamp * 1000 }},
        endTimestampMs: {{ $consultation->end_date_time->timestamp * 1000 }},
        
        startTimer: 0,
        sessionTimer: 0,
        activeStarted: false,
        activeExpired: false,

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

            // Auto background polling for new messages every 1.5 seconds
            setInterval(() => {
                this.fetchMessages();
            }, 1500);
        },

        syncTimers() {
            const now = Date.now();
            const isConfirmedOrActive = {{ in_array($consultation->status, ['confirmed', 'active']) ? 'true' : 'false' }};
            
            // Check if session start time is reached or doctor has confirmed
            if (!isConfirmedOrActive && now < this.startTimestampMs) {
                this.activeStarted = false;
                this.startTimer = Math.max(0, Math.floor((this.startTimestampMs - now) / 1000));
            } else {
                this.activeStarted = true;
            }

            // Calculate remaining seconds to end time
            if (this.activeStarted) {
                if (now >= this.endTimestampMs) {
                    this.activeExpired = true;
                    this.sessionTimer = 0;
                } else {
                    this.activeExpired = false;
                    this.sessionTimer = Math.max(0, Math.floor((this.endTimestampMs - now) / 1000));
                }
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
                    const serverMessages = await res.json();
                    let hasNew = false;
                    
                    // Incremental sync without replacing entire array to prevent DOM flicker/glitch
                    for (const sMsg of serverMessages) {
                        const existing = this.messages.find(m => m.id === sMsg.id);
                        if (!existing) {
                            // Match and replace any pending optimistic message
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
