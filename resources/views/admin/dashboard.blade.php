@extends('layouts.app')
@section('title', 'Dashboard Admin | KoLine')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div style="max-width: 1040px; margin: 0 auto;">

    {{-- Header --}}
    <div class="main-header" style="margin-bottom: 2rem; padding-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">Overview Dashboard Admin</h1>
            <div style="font-size: 0.9rem; color: var(--txt-muted);">Analitik platform, aktivitas pengguna, dan pendapatan apotek KoLine · {{ now()->format('d M Y') }}</div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">Kelola User</a>
            <a href="{{ route('admin.dokter.index') }}" class="btn btn-primary btn-sm">Kelola Dokter</a>
        </div>
    </div>

    {{-- Stats Grid (4 Metric Cards) --}}
    <div class="grid grid-4 mb-8" style="gap: 1.25rem;">
        {{-- Card 1: Total Pasien --}}
        <div class="card" style="padding: 1.35rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-3">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.04em;">Total Pasien</span>
                <div style="width: 36px; height: 36px; border-radius: var(--r-md); background: rgba(2, 132, 199, 0.12); color: #0284C7; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--txt-heading); line-height: 1.1; margin-bottom: 0.25rem;">
                {{ number_format($stats['total_users']) }}
            </div>
            <div style="font-size: 0.775rem; color: var(--txt-muted);">Pasien terdaftar di sistem</div>
        </div>

        {{-- Card 2: Total Dokter --}}
        <div class="card" style="padding: 1.35rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-3">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.04em;">Total Dokter</span>
                <div style="width: 36px; height: 36px; border-radius: var(--r-md); background: rgba(13, 148, 136, 0.12); color: #0D9488; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #0D9488; line-height: 1.1; margin-bottom: 0.25rem;">
                {{ number_format($stats['total_doctors']) }}
            </div>
            <div style="font-size: 0.775rem; color: var(--txt-muted);">Dokter spesialis aktif</div>
        </div>

        {{-- Card 3: Total Konsultasi --}}
        <div class="card" style="padding: 1.35rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-3">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.04em;">Total Konsultasi</span>
                <div style="width: 36px; height: 36px; border-radius: var(--r-md); background: rgba(139, 92, 246, 0.12); color: #8B5CF6; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #8B5CF6; line-height: 1.1; margin-bottom: 0.25rem;">
                {{ number_format($stats['total_consultations']) }}
            </div>
            <div style="font-size: 0.775rem; color: var(--txt-muted);">Sesi konsultasi dibuat</div>
        </div>

        {{-- Card 4: Konsultasi Aktif --}}
        <div class="card" style="padding: 1.35rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-3">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.04em;">Konsultasi Aktif</span>
                <div style="width: 36px; height: 36px; border-radius: var(--r-md); background: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #10B981; line-height: 1.1; margin-bottom: 0.25rem;">
                {{ number_format($stats['active_consultations']) }}
            </div>
            <div style="font-size: 0.775rem; color: var(--txt-muted);">Sesi berjalan saat ini</div>
        </div>
    </div>

    {{-- Main Grid Row 1 (Chart & Financial Overview) --}}
    <div class="grid grid-2 mb-8" style="gap: 1.5rem; align-items: stretch;">
        {{-- Chart Card --}}
        <div class="card" style="padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle); display: flex; flex-direction: column; justify-content: space-between;">
            <div class="flex-between items-center mb-4 pb-3" style="border-bottom: 1px solid var(--bdr-subtle);">
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" fill="none" stroke="#0284C7" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--txt-heading); margin: 0;">Tren Konsultasi Bulanan</h3>
                </div>
                <span class="badge badge-primary" style="font-size: 0.75rem;">Tahun {{ date('Y') }}</span>
            </div>
            <div style="flex: 1; position: relative; min-height: 220px;">
                <canvas id="consultationChart"></canvas>
            </div>
        </div>

        {{-- Financial & Operations Card --}}
        <div class="card" style="padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div class="flex-between items-center mb-4 pb-3" style="border-bottom: 1px solid var(--bdr-subtle);">
                    <div class="flex items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--txt-heading); margin: 0;">Pendapatan & Transaksi Apotek</h3>
                    </div>
                </div>

                <div class="grid grid-2 gap-4 mb-4">
                    <div style="padding: 1.125rem; background: var(--bg-surface); border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle);">
                        <div style="font-size: 0.775rem; color: var(--txt-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">Total Revenue</div>
                        <div style="font-size: 1.45rem; font-weight: 800; color: #10B981; line-height: 1.2;">
                            Rp {{ number_format($stats['revenue'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="padding: 1.125rem; background: var(--bg-surface); border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle);">
                        <div style="font-size: 0.775rem; color: var(--txt-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">Pesanan Apotek</div>
                        <div style="font-size: 1.45rem; font-weight: 800; color: #0284C7; line-height: 1.2;">
                            {{ number_format($stats['total_orders']) }} Order
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 1rem;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="justify-content: center;">
                    Manajemen User
                </a>
                <a href="{{ route('admin.apotek.index') }}" class="btn btn-outline btn-sm" style="justify-content: center;">
                    Manajemen Apotek
                </a>
            </div>
        </div>
    </div>

    {{-- Main Grid Row 2 (Recent Data Tables) --}}
    <div class="grid grid-2" style="gap: 1.5rem; align-items: start;">
        {{-- Recent Consultations --}}
        <div class="card" style="padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-4 pb-3" style="border-bottom: 1px solid var(--bdr-subtle);">
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" fill="none" stroke="#8B5CF6" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--txt-heading); margin: 0;">Konsultasi Terbaru</h3>
                </div>
            </div>

            @if($recentConsultations->isEmpty())
                <div style="text-align: center; padding: 2rem 1rem; color: var(--txt-muted);">
                    <div style="font-size: 0.875rem;">Belum ada riwayat konsultasi.</div>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($recentConsultations as $c)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1rem; background: var(--bg-surface); border-radius: var(--r-md); border: 1px solid var(--bdr-subtle);">
                            <div>
                                <div style="font-weight: 700; font-size: 0.875rem; color: var(--txt-heading);">{{ $c->patient->name }}</div>
                                <div style="font-size: 0.775rem; color: var(--txt-muted);">Dokter: {{ $c->doctor->user->name }}</div>
                            </div>
                            <span class="badge badge-{{ $c->status_color }}" style="font-size: 0.75rem;">{{ $c->status_label }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Orders --}}
        <div class="card" style="padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
            <div class="flex-between items-center mb-4 pb-3" style="border-bottom: 1px solid var(--bdr-subtle);">
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" fill="none" stroke="#0284C7" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--txt-heading); margin: 0;">Pesanan Apotek Terbaru</h3>
                </div>
            </div>

            @if($recentOrders->isEmpty())
                <div style="text-align: center; padding: 2rem 1rem; color: var(--txt-muted);">
                    <div style="font-size: 0.875rem;">Belum ada pesanan obat.</div>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($recentOrders as $o)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1rem; background: var(--bg-surface); border-radius: var(--r-md); border: 1px solid var(--bdr-subtle);">
                            <div>
                                <div style="font-weight: 700; font-size: 0.875rem; color: var(--txt-heading);">{{ $o->order_number }}</div>
                                <div style="font-size: 0.775rem; color: var(--txt-muted);">{{ $o->user->name }}</div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.25rem;">
                                <div style="font-weight: 700; font-size: 0.875rem; color: #10B981; line-height: 1.2;">Rp {{ number_format($o->total, 0, ',', '.') }}</div>
                                <span class="badge badge-teal" style="font-size: 0.675rem; text-transform: uppercase;">{{ $o->status }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
const ctx = document.getElementById('consultationChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($chartData, 'label')) !!},
            datasets: [{
                label: 'Konsultasi',
                data: {!! json_encode(array_column($chartData, 'count')) !!},
                backgroundColor: 'rgba(2, 132, 199, 0.35)',
                borderColor: '#0284C7',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94A3B8' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94A3B8', stepSize: 1 }, beginAtZero: true }
            }
        }
    });
}
</script>
@endpush
@endsection
