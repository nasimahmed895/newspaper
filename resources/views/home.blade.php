@extends('layouts.app')

@section('title', 'WorldPulse24 — Breaking News, World Events & In-Depth Analysis')
@section('meta_description', 'WorldPulse24 delivers trusted, breaking news and in-depth reporting on world events, politics, business, technology, science, health, sports, and entertainment.')

@section('content')

{{-- Mobile Float Buttons --}}
<div class="fixed right-0 top-1/2 -translate-y-1/2 z-30 flex flex-col gap-2 lg:hidden">
    <button type="button" id="trending-btn" class="bg-red-600 text-white py-3 px-2 rounded-l-xl shadow-lg text-[10px] font-bold flex flex-col items-center gap-1 hover:bg-red-700 transition">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
        <span>Hot</span>
    </button>
</div>

{{-- Trending Drawer --}}
<div id="trending-overlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden" style="opacity:0;transition:opacity .3s;"></div>
<div id="trending-drawer" class="fixed top-0 right-0 bottom-0 z-50 bg-white shadow-2xl lg:hidden flex flex-col" style="width:90%;max-width:340px;transform:translateX(100%);transition:transform .3s ease;">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <span class="font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
            Trending Now
        </span>
        <button class="trending-close p-1 text-gray-400 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
        @forelse($trending as $i => $art)
        <a href="{{ route('articles.show', $art->slug) }}" class="flex items-start gap-3 group">
            <span class="text-xl font-black {{ $i < 3 ? 'text-red-500' : 'text-gray-200' }}">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
            <div>
                <h4 class="text-sm font-medium text-gray-800 group-hover:text-red-600 transition line-clamp-2">{{ $art->title }}</h4>
                <span class="text-xs text-gray-400">{{ $art->published_at?->diffForHumans() }}</span>
            </div>
        </a>
        @empty
        <p class="text-sm text-gray-400">No trending stories yet.</p>
        @endforelse
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- BREAKING NEWS TICKER --}}
    @if($trending->isNotEmpty())
    <div class="bg-slate-900 text-white rounded-xl mb-8 overflow-hidden flex items-center">
        <span class="bg-red-600 text-white text-xs font-black px-4 py-3 uppercase tracking-wider whitespace-nowrap flex-shrink-0">
            🔴 Breaking
        </span>
        <div class="overflow-hidden flex-1 py-3 px-4">
            <div class="animate-marquee whitespace-nowrap text-sm">
                @foreach($trending as $item)
                    <a href="{{ route('articles.show', $item->slug) }}" class="mr-10 hover:text-red-300 transition">
                        {{ $item->title }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ── HERO SECTION ─────────────────────────────────────────────── --}}
    @if($featured->isNotEmpty())
    <section class="mb-12" aria-label="Featured stories">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Main Hero --}}
            <div class="lg:col-span-2">
                <a href="{{ route('articles.show', $featured[0]->slug) }}" class="group block relative rounded-2xl overflow-hidden aspect-video lg:aspect-[16/9] bg-gray-200">
                    @if($featured[0]->featured_image)
                        <img src="{{ $featured[0]->featured_image }}"
                             alt="{{ $featured[0]->title }}"
                             class="article-card-img w-full h-full object-cover"
                             loading="eager"
                             fetchpriority="high">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-700 to-slate-900 flex items-center justify-center">
                            <span class="text-white text-7xl font-black opacity-20">WP</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="bg-red-600 text-white text-[11px] font-black px-3 py-1 rounded-full uppercase tracking-wide">{{ $featured[0]->category?->name }}</span>
                            @if($featured[0]->is_trending)
                                <span class="bg-orange-500 text-white text-[11px] font-bold px-2 py-1 rounded-full">🔥 Trending</span>
                            @endif
                        </div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-white leading-tight group-hover:text-red-200 transition line-clamp-3">
                            {{ $featured[0]->title }}
                        </h1>
                        <p class="text-gray-300 text-sm mt-2 line-clamp-2 hidden sm:block">{{ $featured[0]->excerpt }}</p>
                        <div class="flex items-center gap-3 mt-3 text-gray-400 text-xs">
                            <span>{{ $featured[0]->author ?? 'WorldPulse24' }}</span>
                            <span>·</span>
                            <span>{{ $featured[0]->published_at?->diffForHumans() }}</span>
                            <span>·</span>
                            <span>{{ $featured[0]->reading_time_minutes }} min read</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Side Featured (3 articles) --}}
            <div class="flex flex-col gap-4">
                @foreach($featured->slice(1, 3) as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="article-card group flex gap-3 bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition p-3">
                    <div class="w-24 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                        @if($article->featured_image)
                            <img src="{{ $article->featured_image }}" alt="{{ $article->title }}"
                                 class="article-card-img w-full h-full object-cover" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300"></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-red-600 uppercase tracking-wide">{{ $article->category?->name }}</span>
                            <h3 class="text-sm font-bold text-gray-900 mt-1 line-clamp-2 group-hover:text-red-600 transition leading-snug">{{ $article->title }}</h3>
                        </div>
                        <span class="text-xs text-gray-400 mt-1">{{ $article->published_at?->diffForHumans() }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── TWO-COLUMN: LATEST + SIDEBAR ─────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">

        {{-- Latest News --}}
        <div class="lg:col-span-2">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-red-600 rounded-full"></div>
                <h2 class="text-xl font-black text-gray-900">Latest News</h2>
            </div>
            <div class="space-y-5">
                @forelse($latest as $article)
                <article class="article-card bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col sm:flex-row gap-0">
                    @if($article->featured_image)
                    <a href="{{ route('articles.show', $article->slug) }}" class="sm:w-48 sm:flex-shrink-0 block overflow-hidden">
                        <img src="{{ $article->featured_image }}"
                             alt="{{ $article->title }}"
                             class="article-card-img w-full h-44 sm:h-full object-cover"
                             loading="lazy"
                             width="192" height="128">
                    </a>
                    @endif
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="{{ route('categories.show', $article->category?->slug) }}"
                                   class="text-[11px] font-black uppercase tracking-wide"
                                   style="color: {{ $article->category?->color ?? '#DC2626' }}">
                                    {{ $article->category?->name }}
                                </a>
                                <span class="text-xs text-gray-300">·</span>
                                <span class="text-xs text-gray-400">{{ $article->published_at?->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-red-600 transition">{{ $article->title }}</a>
                            </h3>
                            <p class="text-gray-500 text-sm line-clamp-2">{{ $article->excerpt }}</p>
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xs text-gray-400">{{ $article->reading_time_minutes }} min read</span>
                            @if($article->is_trending)
                                <span class="text-xs text-orange-500 font-bold">🔥 Trending</span>
                            @endif
                        </div>
                    </div>
                </article>
                @empty
                <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                    <p class="text-gray-400 text-lg font-medium">No articles yet. Check back soon.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-6">
            @php $sidebarTopAd = \App\Models\AdPlacement::getByLocation('sidebar-top'); @endphp
            @if($sidebarTopAd && $sidebarTopAd->code)
                <div class="bg-gray-100 p-4 rounded-xl text-center text-sm">{!! $sidebarTopAd->code !!}</div>
            @endif

            {{-- Trending sidebar --}}
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-5">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
                    <h3 class="font-black text-gray-900">Most Read</h3>
                </div>
                <div class="space-y-4">
                    @forelse($trending as $i => $art)
                    <a href="{{ route('articles.show', $art->slug) }}" class="flex items-start gap-3 group">
                        <span class="text-2xl font-black leading-none {{ $i < 3 ? 'text-red-500' : 'text-gray-200' }}">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 group-hover:text-red-600 transition line-clamp-2 leading-snug">{{ $art->title }}</h4>
                            <span class="text-xs text-gray-400">{{ $art->published_at?->diffForHumans() }}</span>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-400">No trending articles yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Categories sidebar --}}
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <h3 class="font-black text-gray-900 mb-4">News Sections</h3>
                <div class="space-y-1">
                    @foreach($categories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-gray-50 transition group">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $cat->color ?? '#6B7280' }}"></span>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-red-600 transition">{{ $cat->name }}</span>
                        </div>
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">{{ $cat->articles_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            @php $sidebarBottomAd = \App\Models\AdPlacement::getByLocation('sidebar-bottom'); @endphp
            @if($sidebarBottomAd && $sidebarBottomAd->code)
                <div class="bg-gray-100 p-4 rounded-xl text-center text-sm">{!! $sidebarBottomAd->code !!}</div>
            @endif
        </aside>
    </div>

    {{-- ── CATEGORY SECTIONS ─────────────────────────────────────────── --}}
    @foreach($categorySections as $cat)
    <section class="mb-14" aria-label="{{ $cat->name }} news">
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-6 pb-3 border-b-2" style="border-color: {{ $cat->color ?? '#DC2626' }}">
            <div class="flex items-center gap-3">
                <div class="w-3 h-7 rounded-sm" style="background-color: {{ $cat->color ?? '#DC2626' }}"></div>
                <h2 class="text-xl font-black text-gray-900">{{ $cat->name }}</h2>
            </div>
            <a href="{{ route('categories.show', $cat->slug) }}"
               class="text-sm font-semibold hover:underline flex items-center gap-1"
               style="color: {{ $cat->color ?? '#DC2626' }}">
                All {{ $cat->name }} <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Article Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($cat->section_articles as $article)
            <article class="article-card bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition group">
                <a href="{{ route('articles.show', $article->slug) }}" class="block aspect-video overflow-hidden bg-gray-100">
                    @if($article->featured_image)
                        <img src="{{ $article->featured_image }}"
                             alt="{{ $article->title }}"
                             class="article-card-img w-full h-full object-cover"
                             loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, {{ $cat->color ?? '#6B7280' }}33, {{ $cat->color ?? '#6B7280' }}66)">
                            <span class="text-4xl font-black opacity-30" style="color: {{ $cat->color ?? '#6B7280' }}">{{ $cat->name[0] }}</span>
                        </div>
                    @endif
                </a>
                <div class="p-4">
                    <h3 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 transition leading-snug mb-2">
                        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                    </h3>
                    <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $article->excerpt }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">{{ $article->published_at?->diffForHumans() }}</span>
                        <span class="text-xs text-gray-400">{{ $article->reading_time_minutes }} min</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>

    {{-- In-feed ad after first 2 categories --}}
    @if($loop->index === 1)
        @php $infeedAd = \App\Models\AdPlacement::getByLocation('in-feed'); @endphp
        @if($infeedAd && $infeedAd->code)
            <div class="mb-10 p-4 bg-gray-50 rounded-xl text-center text-sm border border-gray-200">{!! $infeedAd->code !!}</div>
        @endif
    @endif
    @endforeach

</div>

<script>
(function () {
    function makeDrawer(btnId, overlayId, drawerId, closeClass) {
        var btn = document.getElementById(btnId);
        var ov = document.getElementById(overlayId);
        var dr = document.getElementById(drawerId);
        if (!btn || !ov || !dr) return;
        function open() { dr.style.transform = 'translateX(0)'; ov.classList.remove('hidden'); setTimeout(function () { ov.style.opacity = '1'; }, 10); document.body.style.overflow = 'hidden'; }
        function close() { dr.style.transform = 'translateX(100%)'; ov.style.opacity = '0'; document.body.style.overflow = ''; setTimeout(function () { ov.classList.add('hidden'); }, 300); }
        btn.addEventListener('click', open);
        dr.querySelectorAll('.' + closeClass).forEach(function (el) { el.addEventListener('click', close); });
        ov.addEventListener('click', close);
    }
    makeDrawer('trending-btn', 'trending-overlay', 'trending-drawer', 'trending-close');
})();
</script>
@endsection
