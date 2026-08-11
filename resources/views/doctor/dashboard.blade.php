@extends('layouts.app')
@section('title', 'Dashboard Dokter | KoLine')

@section('content')
<div style="max-width: 100%;" x-data="doctorDashboardApp()" x-init="initDashboard()">

    @php
        $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', auth()->user()->name);
        $words = explode(' ', trim($cleanName));
        $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0] ?? 'D', 1, 1)));
    @endphp

    {{-- Top Executive Header Bar --}}
    <div class="card mb-8" style="padding: 1.75rem 2.25rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle); border-radius: var(--r-xl); box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                {{-- Doctor Avatar Circle with Subtle Brand Glow --}}
                <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, rgba(2, 132, 199, 0.2), rgba(13, 148, 136, 0.2)); border: 2px solid var(--clr-brand); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--clr-brand-light); font-size: 1.35rem; flex-shrink: 0; box-shadow: 0 0 20px rgba(2, 132, 199, 0.25);">
                    {{ $initials }}
                </div>

                <div style="display: flex; flex-direction: column; justify-content: center; gap: 0.35rem;">
                    {{-- Row 1: Doctor Name + Specialization Tag --}}
                    <div style="display: flex; align-items: center; gap: 0.875rem; flex-wrap: wrap;">
                        <h1 style="font-size: 1.5rem; font-weight: 800; color: #F8FAFC; margin: 0; line-height: 1.2; letter-spacing: -0.01em;">
                            {{ auth()->user()->name }}
                        </h1>
                        <span class="badge badge-teal" style="font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: var(--r-full); text-transform: uppercase; letter-spacing: 0.04em;">
                            {{ $doctor->specialization->name ?? 'Dokter Spesialis' }}
                        </span>
                    </div>

                    {{-- Row 2: Clean Inline Text Metadata with SVG Icons & Bullet Separators --}}
                    <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; color: #94A3B8; flex-wrap: wrap;">
                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #CBD5E1; font-weight: 500;">
                            <svg width="15" height="15" fill="none" stroke="#38BDF8" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ $doctor->hospital ?? 'RS Partner KoLine' }}
                        </span>

                        <span style="color: rgba(255,255,255,0.2);">•</span>

                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #CBD5E1;">
                            <svg width="15" height="15" fill="none" stroke="#2DD4BF" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            STR: <strong style="color: #F8FAFC; font-weight: 700; font-family: monospace;">{{ $doctor->str_number ?? 'STR-001-2024' }}</strong>
                        </span>

                        <span style="color: rgba(255,255,255,0.2);">•</span>

                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #CBD5E1;">
                            <svg width="15" height="15" fill="#F59E0B" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            Rating: <strong style="color: #F59E0B; font-weight: 700;">{{ number_format($doctor->rating ?? 5.0, 1) }}</strong>
                            <span style="color: #64748B; font-size: 0.8rem;">({{ $doctor->total_reviews ?? 0 }} ulasan)</span>
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <span class="badge badge-success" style="padding: 0.5rem 1.125rem; font-size: 0.8rem; font-weight: 700; border-radius: var(--r-full); display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(16, 185, 129, 0.12); color: #34D399; border: 1px solid rgba(52, 211, 153, 0.3);">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #34D399; box-shadow: 0 0 10px #34D399;"></span> Praktik Online Aktif
                </span>
            </div>

        </div>
    </div>

    {{-- Metric Grid Cards (4 SaaS Cards) --}}
    <div class="grid grid-4 mb-8" style="gap: 1.25rem;">
        
        {{-- Metric 1: Total Pasien --}}
        <div class="card" style="padding: 1.35rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-3">
                <span style="font-size: 0.825rem; font-weight: 600; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.04em;">Total Pasien</span>
                <div style="width: 36px; height: 36px; border-radius: var(--r-md); background: rgba(2, 132, 199, 0.12); color: #0284C7; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--txt-heading); line-height: 1.1; margin-bottom: 0.25rem;">
                {{ number_format($stats['total_patients']) }}
            </div>
            <div style="font-size: 0.775rem; color: var(--txt-muted);">Pasien terdaftar & ditangani</div>
        </div>

        {{-- Metric 2: Menunggu Konfirmasi --}}
        <div class="card" style="padding: 1.35rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-3">
                <span style="font-size: 0.825rem; font-weight: 600; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.04em;">Menunggu Konfirmasi</span>
                <div style="width: 36px; height: 36px; border-radius: var(--r-md); background: rgba(245, 158, 11, 0.12); color: #F59E0B; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #F59E0B; line-height: 1.1; margin-bottom: 0.25rem;" x-text="pendingCount">
                {{ $stats['pending'] }}
            </div>
            <div style="font-size: 0.775rem; color: var(--txt-muted);">Permintaan janji konsultasi</div>
        </div>

        {{-- Metric 3: Konsultasi Aktif --}}
        <div class="card" style="padding: 1.35rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-3">
                <span style="font-size: 0.825rem; font-weight: 600; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.04em;">Konsultasi Aktif</span>
                <div style="width: 36px; height: 36px; border-radius: var(--r-md); background: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #10B981; line-height: 1.1; margin-bottom: 0.25rem;" x-text="activeCount">
                {{ $stats['active'] }}
            </div>
            <div style="font-size: 0.775rem; color: var(--txt-muted);">Sesi percakapan berlangsung</div>
        </div>

        {{-- Metric 4: Selesai --}}
        <div class="card" style="padding: 1.35rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-3">
                <span style="font-size: 0.825rem; font-weight: 600; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.04em;">Selesai Ditangani</span>
                <div style="width: 36px; height: 36px; border-radius: var(--r-md); background: rgba(139, 92, 246, 0.12); color: #8B5CF6; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #8B5CF6; line-height: 1.1; margin-bottom: 0.25rem;" x-text="completedCount">
                {{ $stats['completed'] }}
            </div>
            <div style="font-size: 0.775rem; color: var(--txt-muted);">Riwayat konsultasi tuntas</div>
        </div>

    </div>

    {{-- Workstation Grid (2 Columns Equal Height) --}}
    <div class="grid grid-2" style="gap: 1.5rem; align-items: stretch;">
        
        {{-- Pending Consultations Column --}}
        <div class="card" style="padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div class="flex-between items-center mb-5" style="border-bottom: 1px solid var(--bdr-subtle); padding-bottom: 0.875rem;">
                    <div class="flex items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--txt-heading); margin: 0;">Antrean Menunggu Konfirmasi</h3>
                    </div>
                    <span style="font-size: 0.775rem; font-weight: 600; color: var(--txt-muted); background: var(--bg-surface); padding: 0.25rem 0.625rem; border-radius: var(--r-sm); border: 1px solid var(--bdr-subtle);">
                        <span x-text="pendingList.length"></span> Pasien
                    </span>
                </div>

                <div x-show="pendingList.length === 0" style="text-align: center; padding: 2.75rem 1rem; color: var(--txt-muted);">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 0.75rem; opacity: 0.4;"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div style="font-weight: 600; color: var(--txt-body); font-size: 0.9rem; margin-bottom: 0.25rem;">Tidak Ada Antrean Baru</div>
                    <div style="font-size: 0.8rem;">Semua janji konsultasi telah disetujui.</div>
                </div>

                <div x-show="pendingList.length > 0" style="display: flex; flex-direction: column; gap: 1rem;">
                    <template x-for="item in pendingList" :key="item.id">
                        <div style="padding: 1rem; background: var(--bg-surface); border: 1px solid var(--bdr-subtle); border-radius: var(--r-md);">
                            <div class="flex-between items-center mb-2">
                                <div class="flex items-center gap-3">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(2, 132, 199, 0.15); color: var(--clr-brand-light); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem;" x-text="item.patient_initial">
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.925rem; color: var(--txt-heading);" x-text="item.patient_name"></div>
                                        <div style="font-size: 0.775rem; color: var(--txt-muted);" x-text="item.date + ' · ' + item.time">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="confirmConsultation(item)" class="btn btn-primary btn-sm" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                                        ✓ Konfirmasi
                                    </button>
                                    <a :href="item.show_url" class="btn btn-outline btn-sm" style="font-size: 0.8rem; padding: 0.35rem 0.625rem;">
                                        Detail →
                                    </a>
                                </div>
                            </div>
                            <div style="font-size: 0.825rem; color: var(--txt-body); background: var(--bg-card); padding: 0.625rem; border-radius: var(--r-sm); border: 1px solid var(--bdr-subtle);">
                                <strong>Keluhan:</strong> <span x-text="item.complaint"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div style="margin-top: 1.5rem; border-top: 1px solid var(--bdr-subtle); padding-top: 1rem;">
                <a href="{{ route('doctor.consultations') }}" class="btn btn-ghost btn-block btn-sm" style="color: var(--txt-muted); text-align: center;">
                    Kelola Antrean Pasien →
                </a>
            </div>
        </div>

        {{-- Active Consultations Column --}}
        <div class="card" style="padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div class="flex-between items-center mb-5" style="border-bottom: 1px solid var(--bdr-subtle); padding-bottom: 0.875rem;">
                    <div class="flex items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--txt-heading); margin: 0;">Sesi Konsultasi Aktif</h3>
                    </div>
                    <span style="font-size: 0.775rem; font-weight: 600; color: var(--txt-muted); background: var(--bg-surface); padding: 0.25rem 0.625rem; border-radius: var(--r-sm); border: 1px solid var(--bdr-subtle);">
                        <span x-text="activeList.length"></span> Ruang Praktek
                    </span>
                </div>

                <div x-show="activeList.length === 0" style="text-align: center; padding: 2.75rem 1rem; color: var(--txt-muted);">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 0.75rem; opacity: 0.4;"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <div style="font-weight: 600; color: var(--txt-body); font-size: 0.9rem; margin-bottom: 0.25rem;">Tidak Ada Chat Berlangsung</div>
                    <div style="font-size: 0.8rem;">Sesi aktif dengan pasien akan muncul di sini.</div>
                </div>

                <div x-show="activeList.length > 0" style="display: flex; flex-direction: column; gap: 1rem;">
                    <template x-for="item in activeList" :key="item.id">
                        <div style="padding: 1rem; background: var(--bg-surface); border: 1px solid var(--bdr-subtle); border-radius: var(--r-md);">
                            <div class="flex-between items-center">
                                <div class="flex items-center gap-3">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); color: #10B981; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem;" x-text="item.patient_initial">
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.925rem; color: var(--txt-heading);" x-text="item.patient_name"></div>
                                        <div style="font-size: 0.775rem; color: #10B981; font-weight: 600;">
                                            ● Sesi Chat Berlangsung
                                        </div>
                                    </div>
                                </div>
                                <a :href="item.show_url" class="btn btn-primary btn-sm" style="font-size: 0.8rem; padding: 0.35rem 0.875rem;">
                                    Masuk Chat →
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div style="margin-top: 1.5rem; border-top: 1px solid var(--bdr-subtle); padding-top: 1rem;">
                <a href="{{ route('consultations.index') }}" class="btn btn-ghost btn-block btn-sm" style="color: var(--txt-muted); text-align: center;">
                    Lihat Semua Riwayat Konsultasi →
                </a>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
function doctorDashboardApp() {
    return {
        pendingCount: {{ $stats['pending'] }},
        activeCount: {{ $stats['active'] }},
        completedCount: {{ $stats['completed'] }},
        pendingList: @json($pendingList),
        activeList: @json($activeList),
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',

        initDashboard() {
            // Run immediate poll on mount
            this.pollConsultations();

            // Start real-time polling every 1 second (1000ms)
            setInterval(() => {
                this.pollConsultations();
            }, 1000);
        },

        async confirmConsultation(item) {
            try {
                const res = await fetch(item.confirm_url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                if (res.ok) {
                    // Immediately move item from pending to active for 0ms glitch-free UI response
                    this.pendingList = this.pendingList.filter(p => p.id !== item.id);
                    this.pendingCount = Math.max(0, this.pendingCount - 1);
                    this.activeCount += 1;
                    // Redirect to consultation room
                    window.location.href = item.show_url;
                } else {
                    const data = await res.json().catch(() => ({}));
                    alert('Gagal konfirmasi: ' + (data.message || 'Silakan coba lagi.'));
                }
            } catch (e) {
                console.error(e);
            }
        },

        async pollConsultations() {
            try {
                const res = await fetch('{{ route('doctor.consultations.poll') }}', {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    if (data.pending_count !== undefined) {
                        this.pendingCount = data.pending_count;
                        this.activeCount = data.active_count;
                        this.completedCount = data.completed_count;
                        if (data.pending_consultations) this.pendingList = data.pending_consultations;
                        if (data.active_consultations) this.activeList = data.active_consultations;
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }
    };
}
</script>
@endpush
@endsection
