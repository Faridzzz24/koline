@extends('layouts.guest')
@section('title', 'Artikel Kesehatan')
@section('content')
<div style="padding-top:80px;">
    <div style="background:var(--bg-surface);border-bottom:1px solid var(--bdr-default);padding:var(--sp-8) 0;">
        <div class="container">
            <h1 style="font-size:var(--text-3xl);font-weight:800;">📰 Artikel <span class="text-gradient">Kesehatan</span></h1>
            <p style="color:var(--txt-muted);">Tips & informasi kesehatan dari para ahli</p>
        </div>
    </div>
    <div class="container" style="padding:var(--sp-8) var(--sp-6);">
        {{-- Category Filter --}}
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('articles.index') }}" class="badge {{ !request('category') ? 'badge-primary' : 'badge-muted' }}" style="padding:var(--sp-2) var(--sp-4);cursor:pointer;font-size:0.8rem;">Semua</a>
            @foreach($categories as $cat)
                <a href="{{ route('articles.index', ['category' => $cat]) }}" class="badge {{ request('category') === $cat ? 'badge-primary' : 'badge-muted' }}" style="padding:var(--sp-2) var(--sp-4);cursor:pointer;font-size:0.8rem;">
                    {{ match($cat) { 'kesehatan_umum' => 'Kesehatan Umum', 'gizi' => 'Gizi & Nutrisi', 'olahraga' => 'Olahraga', 'mental_health' => 'Kesehatan Mental', 'tips_dokter' => 'Tips Dokter', 'penyakit' => 'Penyakit', 'ibu_anak' => 'Ibu & Anak', default => $cat } }}
                </a>
            @endforeach
        </div>

        @if($articles->isEmpty())
            <div style="text-align:center;padding:var(--sp-16) 0;">
                <div style="font-size:4rem;margin-bottom:var(--sp-4);">📰</div>
                <h3>Belum ada artikel</h3>
            </div>
        @else
            <div class="grid grid-3">
                @foreach($articles as $article)
                    <article class="article-card">
                        <div class="article-img" style="font-size:3.5rem;">
                            {{ match($article->category) { 'kesehatan_umum' => '🏥', 'gizi' => '🥗', 'olahraga' => '🏃', 'mental_health' => '🧠', 'tips_dokter' => '👨‍⚕️', 'penyakit' => '🦠', 'ibu_anak' => '👶', default => '📰' } }}
                        </div>
                        <div class="article-body">
                            <div class="article-meta">
                                <span class="badge badge-primary">{{ $article->category_label }}</span>
                                <span class="text-xs text-muted">👁 {{ number_format($article->views) }}</span>
                            </div>
                            <h3 class="article-title">{{ $article->title }}</h3>
                            <p class="article-excerpt">{{ $article->excerpt }}</p>
                        </div>
                        <div class="article-footer">
                            <div class="flex items-center gap-2">
                                <div class="doctor-avatar-placeholder" style="width:26px;height:26px;font-size:0.7rem;">{{ substr($article->author->name, 0, 1) }}</div>
                                <span class="text-xs text-muted">{{ $article->author->name }}</span>
                                <span class="text-xs text-muted">· {{ $article->published_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-primary btn-sm">Baca →</a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div style="margin-top:var(--sp-8);">{{ $articles->withQueryString()->links('vendor.pagination.custom') }}</div>
        @endif
    </div>
</div>
@endsection
