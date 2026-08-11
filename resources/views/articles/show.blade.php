@extends('layouts.guest')
@section('title', $article->title)
@section('content')
<div style="padding-top:80px;">
    <div class="container" style="padding:var(--sp-10) var(--sp-6);max-width:900px;margin:0 auto;">
        <a href="{{ route('articles.index') }}" class="btn btn-ghost btn-sm mb-6">← Kembali</a>
        <div class="flex gap-3 mb-4">
            <span class="badge badge-primary">{{ $article->category_label }}</span>
            <span class="text-xs text-muted">👁 {{ number_format($article->views) }} pembaca</span>
            <span class="text-xs text-muted">📅 {{ $article->published_at->format('d M Y') }}</span>
        </div>
        <h1 style="font-size:clamp(1.75rem,4vw,2.75rem);font-weight:800;color:var(--txt-primary);line-height:1.3;margin-bottom:var(--sp-6);">{{ $article->title }}</h1>
        <div class="flex items-center gap-3 mb-8 pb-6" style="border-bottom:1px solid var(--bdr-default);">
            <div class="doctor-avatar-placeholder" style="width:44px;height:44px;">{{ substr($article->author->name, 0, 1) }}</div>
            <div>
                <div style="font-weight:600;color:var(--txt-primary);">{{ $article->author->name }}</div>
                <div style="font-size:var(--text-xs);color:var(--txt-muted);">Diterbitkan {{ $article->published_at->diffForHumans() }}</div>
            </div>
        </div>

        {{-- Content --}}
        <div style="color:var(--txt-sec);line-height:1.9;font-size:var(--text-lg);" class="article-content">
            {!! $article->content !!}
        </div>

        {{-- Related --}}
        @if($related->isNotEmpty())
            <div style="margin-top:var(--sp-12);padding-top:var(--sp-8);border-top:1px solid var(--bdr-default);">
                <h3 class="mb-6">📰 Artikel Terkait</h3>
                <div class="grid grid-3">
                    @foreach($related as $rel)
                        <a href="{{ route('articles.show', $rel) }}" class="card card-sm" style="text-decoration:none;">
                            <div class="badge badge-muted mb-2">{{ $rel->category_label }}</div>
                            <div style="font-weight:700;color:var(--txt-primary);margin-bottom:var(--sp-2);font-size:var(--text-sm);">{{ Str::limit($rel->title, 60) }}</div>
                            <div style="font-size:var(--text-xs);color:var(--txt-muted);">{{ $rel->published_at->diffForHumans() }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.article-content h2 { font-size: 1.5rem; font-weight: 700; color: var(--txt-primary); margin: 2rem 0 1rem; }
.article-content h3 { font-size: 1.25rem; font-weight: 600; color: var(--txt-primary); margin: 1.5rem 0 0.75rem; }
.article-content p { margin-bottom: 1.25rem; }
.article-content ul, .article-content ol { margin: 1rem 0 1.25rem 1.5rem; color: var(--txt-sec); }
.article-content li { margin-bottom: 0.5rem; }
.article-content strong { color: var(--txt-primary); font-weight: 600; }
</style>
@endpush
@endsection
