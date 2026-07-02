@extends('layouts.app')

@section('title', config('app.name') . ' - Your Trusted News Source')

@section('content')
{{-- Mobile Floating Buttons --}}
<div class="fixed right-0 top-1/2 -translate-y-1/2 z-30 flex flex-col gap-3 lg:hidden">
    <button type="button" id="trending-btn" class="bg-red-600 text-white py-3 px-2 rounded-l-lg shadow-lg text-xs font-bold flex flex-col items-center gap-1 hover:bg-red-700 transition">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
        <span>Trending</span>
    </button>
    <button type="button" id="categories-btn" class="bg-blue-600 text-white py-3 px-2 rounded-l-lg shadow-lg text-xs font-bold flex flex-col items-center gap-1 hover:bg-blue-700 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        <span>Categories</span>
    </button>
</div>

{{-- Trending Drawer Overlay --}}
<div id="trending-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300" style="opacity: 0;"></div>

{{-- Trending Drawer --}}
<div id="trending-drawer" class="fixed top-0 right-0 bottom-0 z-50 bg-white shadow-2xl transition-transform duration-300 lg:hidden" style="width: 90%; max-width: 360px; transform: translateX(100%);">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <span class="text-lg font-bold text-gray-900 flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
            Trending Now
        </span>
        <button type="button" class="trending-close p-1 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="px-5 py-4 overflow-y-auto h-full pb-20">
        <div class="space-y-4">
            @forelse($trending as $index => $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="flex items-start space-x-3 group">
                <span class="text-2xl font-bold {{ $index < 3 ? 'text-blue-600' : 'text-gray-300' }}">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition line-clamp-2">{{ $article->title }}</h4>
                    <span class="text-xs text-gray-400">{{ $article->published_at?->diffForHumans() }}</span>
                </div>
            </a>
            @empty
            <p class="text-sm text-gray-500">No trending articles yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Categories Drawer Overlay --}}
<div id="categories-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300" style="opacity: 0;"></div>

{{-- Categories Drawer --}}
<div id="categories-drawer" class="fixed top-0 right-0 bottom-0 z-50 bg-white shadow-2xl transition-transform duration-300 lg:hidden" style="width: 90%; max-width: 360px; transform: translateX(100%);">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <span class="text-lg font-bold text-gray-900">Categories</span>
        <button type="button" class="categories-close p-1 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="px-5 py-4 overflow-y-auto h-full pb-20">
        <div class="space-y-2">
            @foreach($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-50 transition">
                <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $category->articles_count }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breaking News Ticker --}}
    @if($trending->isNotEmpty())
    <div class="bg-red-600 text-white rounded-lg mb-8 overflow-hidden">
        <div class="flex items-center px-4 py-2">
            <span class="font-bold uppercase text-xs mr-4 bg-white text-red-600 px-2 py-1 rounded">Trending</span>
            <div class="overflow-hidden flex-1">
                <div class="animate-marquee whitespace-nowrap">
                    @foreach($trending as $item)
                        <a href="{{ route('articles.show', $item->slug) }}" class="mr-8 hover:underline text-sm">{{ $item->title }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Featured Articles --}}
    @if($featured->isNotEmpty())
    <section class="mb-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Main Featured --}}
            <div class="lg:col-span-2">
                <a href="{{ route('articles.show', $featured[0]->slug) }}" class="group block">
                    <div class="relative rounded-xl overflow-hidden bg-gray-200 aspect-video lg:aspect-[21/9]">
                        @if($featured[0]->featured_image)
                            <img src="{{ $featured[0]->featured_image }}" alt="{{ $featured[0]->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-700">
                                <span class="text-white text-6xl font-bold">{{ $featured[0]->category?->name[0] ?? 'N' }}</span>
                            </div>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                            <span class="inline-block bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-2">{{ $featured[0]->category?->name }}</span>
                            <h2 class="text-2xl lg:text-3xl font-bold text-white group-hover:text-blue-200 transition">{{ $featured[0]->title }}</h2>
                            <p class="text-gray-300 mt-2 text-sm line-clamp-2">{{ $featured[0]->excerpt }}</p>
                            <span class="text-gray-400 text-xs mt-2 block">{{ $featured[0]->published_at?->diffForHumans() }} · {{ $featured[0]->reading_time_minutes }} min read</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Secondary Featured --}}
            @foreach($featured->slice(1, 2) as $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="group block">
                <div class="relative rounded-xl overflow-hidden bg-gray-200 aspect-video">
                    @if($article->featured_image)
                        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-700 to-gray-900">
                            <span class="text-white text-4xl font-bold">{{ $article->category?->name[0] ?? 'N' }}</span>
                        </div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <span class="inline-block bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-1">{{ $article->category?->name }}</span>
                        <h3 class="text-lg font-bold text-white group-hover:text-blue-200 transition">{{ $article->title }}</h3>
                        <span class="text-gray-400 text-xs mt-1 block">{{ $article->published_at?->diffForHumans() }} · {{ $article->reading_time_minutes }} min read</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
            {{-- Latest News --}}
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Latest News</h2>
                </div>
                <div class="space-y-6">
                    @forelse($latest as $article)
                    <article class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row">
                            @if($article->featured_image)
                            <div class="sm:w-48 sm:flex-shrink-0">
                                <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-48 sm:h-full object-cover">
                            </div>
                            @endif
                            <div class="p-5 flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-xs font-bold text-blue-600 uppercase">{{ $article->category?->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $article->published_at?->diffForHumans() }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-blue-600 transition">{{ $article->title }}</a>
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-2">{{ $article->excerpt }}</p>
                                <div class="flex items-center mt-3 text-xs text-gray-400">
                                    <span>{{ $article->reading_time_minutes }} min read</span>
                                    @if($article->is_trending)
                                        <span class="ml-3 text-red-500 font-semibold">🔥 Trending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                    @empty
                    <div class="text-center py-12 text-gray-500">
                        <p class="text-lg">No articles published yet. Check back soon!</p>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-8">
            {{-- Sidebar Ad --}}
            @php $sidebarTopAd = \App\Models\AdPlacement::getByLocation('sidebar-top'); @endphp
            @if($sidebarTopAd && $sidebarTopAd->code)
                <div class="bg-gray-100 p-4 rounded-lg text-center text-sm">{!! $sidebarTopAd->code !!}</div>
            @endif

            {{-- Trending Sidebar --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                    Trending Now
                </h3>
                <div class="space-y-4">
                    @forelse($trending as $index => $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="flex items-start space-x-3 group">
                        <span class="text-2xl font-bold {{ $index < 3 ? 'text-blue-600' : 'text-gray-300' }}">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition line-clamp-2">{{ $article->title }}</h4>
                            <span class="text-xs text-gray-400">{{ $article->published_at?->diffForHumans() }}</span>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-500">No trending articles yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Categories --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
                <div class="space-y-2">
                    @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $category->articles_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar Bottom Ad --}}
            @php $sidebarBottomAd = \App\Models\AdPlacement::getByLocation('sidebar-bottom'); @endphp
            @if($sidebarBottomAd && $sidebarBottomAd->code)
                <div class="bg-gray-100 p-4 rounded-lg text-center text-sm">{!! $sidebarBottomAd->code !!}</div>
            @endif
        </aside>
    </div>
</div>

<script>
(function() {
    function setupDrawer(btnId, overlayId, drawerId, closeBtnClass) {
        var btn = document.getElementById(btnId);
        var overlay = document.getElementById(overlayId);
        var drawer = document.getElementById(drawerId);
        var closeBtns = drawer ? drawer.querySelectorAll('.' + closeBtnClass) : [];
        if (!btn || !overlay || !drawer) return;

        function open() {
            drawer.style.transform = 'translateX(0)';
            overlay.classList.remove('hidden');
            setTimeout(function() { overlay.style.opacity = '1'; }, 10);
            document.body.style.overflow = 'hidden';
        }

        function close() {
            drawer.style.transform = 'translateX(100%)';
            overlay.style.opacity = '0';
            document.body.style.overflow = '';
            setTimeout(function() { overlay.classList.add('hidden'); }, 300);
        }

        btn.addEventListener('click', open);
        closeBtns.forEach(function(el) { el.addEventListener('click', close); });
        overlay.addEventListener('click', close);
    }

    setupDrawer('trending-btn', 'trending-overlay', 'trending-drawer', 'trending-close');
    setupDrawer('categories-btn', 'categories-overlay', 'categories-drawer', 'categories-close');
})();
</script>
@endsection
