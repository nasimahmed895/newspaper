@extends('layouts.app')

@section('reading_progress', true)

@section('title', $article->seo_title ?? $article->title)
@section('meta_description', $article->seo_description ?? $article->excerpt)
@section('meta_keywords', $article->seo_keywords)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ url('/') }}" class="hover:text-red-600 transition">Home</a>
        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('categories.show', $article->category->slug) }}" class="hover:text-red-600 transition">{{ $article->category->name }}</a>
        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 line-clamp-1">{{ Str::limit($article->title, 55) }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ── ARTICLE MAIN ──────────────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm" itemscope itemtype="https://schema.org/NewsArticle">

                {{-- Hero Image --}}
                @if($article->featured_image)
                <div class="aspect-video overflow-hidden bg-gray-100">
                    <img src="{{ $article->featured_image }}"
                         alt="{{ $article->title }}"
                         class="w-full h-full object-cover"
                         loading="eager"
                         fetchpriority="high"
                         itemprop="image">
                </div>
                @if($article->image_credit)
                <div class="px-6 py-1.5 bg-gray-50 border-b border-gray-100">
                    <p class="text-xs text-gray-400">
                        Image:
                        @if($article->image_source === 'unsplash')
                            Photo by {{ $article->image_credit }} on Unsplash
                        @else
                            {{ $article->image_credit }}
                        @endif
                    </p>
                </div>
                @endif
                @endif

                <div class="p-6 lg:p-9">

                    {{-- Category + Meta --}}
                    <div class="flex flex-wrap items-center gap-3 mb-5">
                        <a href="{{ route('categories.show', $article->category->slug) }}"
                           class="text-xs font-black uppercase tracking-wider px-3 py-1.5 rounded-full text-white"
                           style="background-color: {{ $article->category->color ?? '#DC2626' }}">
                            {{ $article->category->name }}
                        </a>
                        @if($article->is_trending)
                            <span class="text-xs font-bold text-orange-500 bg-orange-50 px-3 py-1.5 rounded-full">🔥 Trending</span>
                        @endif
                        <span class="text-sm text-gray-400" itemprop="datePublished" content="{{ $article->published_at?->toIso8601String() }}">
                            {{ $article->published_at?->format('F j, Y \a\t g:i A') }}
                        </span>
                        @if($article->reading_time_minutes)
                            <span class="text-sm text-gray-400">{{ $article->reading_time_minutes }} min read</span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-gray-900 leading-tight mb-5" itemprop="headline">
                        {{ $article->title }}
                    </h1>

                    {{-- Author + Share Bar --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 py-4 border-y border-gray-100 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-800 rounded-full flex items-center justify-center text-white font-black text-sm flex-shrink-0"
                                 itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <span itemprop="name" class="sr-only">{{ $article->author ?? 'WorldPulse24 Desk' }}</span>
                                {{ strtoupper(($article->author ?? 'W')[0]) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $article->author ?? 'WorldPulse24 Desk' }}</p>
                                <p class="text-xs text-gray-400">WorldPulse24 Staff Reporter</p>
                            </div>
                        </div>
                        {{-- Quick share --}}
                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('articles.show', $article->slug)) }}"
                               target="_blank" rel="noopener"
                               class="w-8 h-8 bg-gray-100 hover:bg-black hover:text-white text-gray-600 rounded-full flex items-center justify-center transition"
                               aria-label="Share on X">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.736-8.844L1.254 2.25H8.08l4.256 5.65 5.908-5.65zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->slug)) }}"
                               target="_blank" rel="noopener"
                               class="w-8 h-8 bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-600 rounded-full flex items-center justify-center transition"
                               aria-label="Share on Facebook">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <button type="button" id="copy-link-btn"
                                class="w-8 h-8 bg-gray-100 hover:bg-green-600 hover:text-white text-gray-600 rounded-full flex items-center justify-center transition"
                                aria-label="Copy link" title="Copy link">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- AI Disclosure --}}
                    <div class="bg-amber-50 border-l-4 border-amber-400 rounded-r-xl p-4 mb-6 flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm">
                            <span class="font-bold text-amber-800">AI-Assisted Reporting</span>
                            <span class="text-amber-700"> — This article was researched and written with AI assistance, then reviewed by our editorial team for accuracy and fairness.
                            <a href="{{ route('editorial-policy') }}" class="underline font-semibold hover:text-amber-900">Our editorial standards →</a></span>
                        </div>
                    </div>

                    {{-- Article Body --}}
                    <div id="article-body"
                         class="prose prose-lg max-w-none prose-headings:font-black prose-headings:text-gray-900 prose-a:text-red-600 prose-a:no-underline hover:prose-a:underline prose-img:rounded-xl prose-blockquote:border-red-600 prose-blockquote:bg-gray-50 prose-blockquote:py-1 prose-blockquote:px-4 prose-blockquote:not-italic"
                         itemprop="articleBody">
                        {!! $article->content !!}
                    </div>

                    {{-- In-Article Ad --}}
                    @php $inArticleAd = \App\Models\AdPlacement::getByLocation('in-article'); @endphp
                    @if($inArticleAd && $inArticleAd->code)
                        <div class="my-8 bg-gray-50 p-4 rounded-xl text-center border border-gray-100">{!! $inArticleAd->code !!}</div>
                    @endif

                    {{-- Tags --}}
                    @if($article->seo_keywords)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Topics</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(array_slice(explode(',', $article->seo_keywords), 0, 8) as $keyword)
                                @if(trim($keyword))
                                <a href="{{ url('/search?q=' . urlencode(trim($keyword))) }}"
                                   class="text-xs bg-gray-100 hover:bg-red-100 hover:text-red-700 text-gray-600 px-3 py-1.5 rounded-full transition font-medium">
                                    {{ trim($keyword) }}
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Source --}}
                    @if($article->source)
                    <p class="mt-4 text-xs text-gray-400">
                        Source:
                        @if($article->source_url)
                            <a href="{{ $article->source_url }}" target="_blank" rel="noopener" class="text-red-600 hover:underline">{{ $article->source }}</a>
                        @else
                            {{ $article->source }}
                        @endif
                    </p>
                    @endif

                    {{-- Author Box (EEAT) --}}
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <div class="bg-slate-50 rounded-2xl p-6 flex flex-col items-center sm:flex-row sm:items-start gap-4 sm:gap-5 text-center sm:text-left">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-800 rounded-full flex items-center justify-center text-white text-2xl font-black flex-shrink-0">
                                {{ strtoupper(($article->author ?? 'WorldPulse24 Desk')[0]) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center justify-center sm:justify-start flex-wrap gap-2 mb-1">
                                    <h3 class="font-black text-gray-900">{{ $article->author ?? 'WorldPulse24 Desk' }}</h3>
                                    <span class="text-[11px] bg-red-100 text-red-700 font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">WorldPulse24 Staff</span>
                                </div>
                                <p class="text-sm text-gray-500 leading-relaxed mt-1">
                                    WorldPulse24 journalists follow rigorous editorial standards — verifying claims, citing credible sources, and presenting balanced perspectives. All AI-assisted content undergoes human editorial review before publication.
                                </p>
                                <a href="{{ route('editorial-policy') }}" class="inline-flex items-center gap-1 text-sm text-red-600 font-semibold mt-3 hover:underline">
                                    Our Editorial Policy
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Full Share Section --}}
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-sm font-bold text-gray-700 mb-3">Share this story</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('articles.show', $article->slug)) }}"
                               target="_blank" rel="noopener"
                               class="flex items-center gap-2 px-4 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-700 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.736-8.844L1.254 2.25H8.08l4.256 5.65 5.908-5.65zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                Post on X
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->slug)) }}"
                               target="_blank" rel="noopener"
                               class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('articles.show', $article->slug)) }}"
                               target="_blank" rel="noopener"
                               class="flex items-center gap-2 px-4 py-2.5 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('articles.show', $article->slug)) }}"
                               target="_blank" rel="noopener"
                               class="flex items-center gap-2 px-4 py-2.5 bg-blue-700 text-white text-sm font-semibold rounded-lg hover:bg-blue-800 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                LinkedIn
                            </a>
                            <button type="button" id="copy-link-full"
                                class="flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span id="copy-label">Copy Link</span>
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Related Articles --}}
            @if($relatedArticles->isNotEmpty())
            <section class="mt-10" aria-label="Related articles">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1 h-6 bg-red-600 rounded-full"></div>
                    <h2 class="text-xl font-black text-gray-900">Related Stories</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($relatedArticles as $related)
                    <article class="article-card bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition group">
                        @if($related->featured_image)
                        <a href="{{ route('articles.show', $related->slug) }}" class="block aspect-video overflow-hidden bg-gray-100">
                            <img src="{{ $related->featured_image }}" alt="{{ $related->title }}"
                                 class="article-card-img w-full h-full object-cover" loading="lazy">
                        </a>
                        @endif
                        <div class="p-4">
                            <span class="text-[11px] font-black uppercase tracking-wide text-red-600">{{ $related->category->name }}</span>
                            <h3 class="text-sm font-bold text-gray-900 mt-1 line-clamp-2 group-hover:text-red-600 transition leading-snug">
                                <a href="{{ route('articles.show', $related->slug) }}">{{ $related->title }}</a>
                            </h3>
                            <span class="text-xs text-gray-400 mt-2 block">{{ $related->published_at?->diffForHumans() }}</span>
                        </div>
                    </article>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        {{-- ── SIDEBAR ────────────────────────────────────────────────── --}}
        <aside class="space-y-6">
            @php $sidebarTopAd = \App\Models\AdPlacement::getByLocation('sidebar-top'); @endphp
            @if($sidebarTopAd && $sidebarTopAd->code)
                <div class="bg-gray-100 p-4 rounded-xl text-center text-sm">{!! $sidebarTopAd->code !!}</div>
            @endif

            <div class="space-y-6 lg:sticky lg:top-20">
                {{-- Article Summary --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h3 class="font-black text-gray-900 mb-3 text-sm uppercase tracking-wide">Quick Summary</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $article->excerpt }}</p>
                </div>

                {{-- Trending in same category --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h3 class="font-black text-gray-900 mb-4 text-sm uppercase tracking-wide">More in {{ $article->category->name }}</h3>
                    <div class="space-y-4">
                        @foreach($relatedArticles->take(3) as $related)
                        <a href="{{ route('articles.show', $related->slug) }}" class="block group">
                            <h4 class="text-sm font-semibold text-gray-800 group-hover:text-red-600 transition line-clamp-2 leading-snug">{{ $related->title }}</h4>
                            <span class="text-xs text-gray-400 mt-0.5 block">{{ $related->published_at?->diffForHumans() }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                @php $sidebarBottomAd = \App\Models\AdPlacement::getByLocation('sidebar-bottom'); @endphp
                @if($sidebarBottomAd && $sidebarBottomAd->code)
                    <div class="bg-gray-100 p-4 rounded-xl text-center text-sm">{!! $sidebarBottomAd->code !!}</div>
                @endif
            </div>
        </aside>
    </div>
</div>

<script>
(function () {
    function copyLink(btn, labelId) {
        if (!btn) return;
        btn.addEventListener('click', function () {
            navigator.clipboard.writeText(window.location.href).then(function () {
                var label = labelId ? document.getElementById(labelId) : btn;
                var orig = label ? label.textContent : '';
                if (label) label.textContent = '✓ Copied!';
                setTimeout(function () { if (label) label.textContent = orig; }, 2000);
            });
        });
    }
    copyLink(document.getElementById('copy-link-btn'));
    copyLink(document.getElementById('copy-link-full'), 'copy-label');
})();
</script>
@endsection
