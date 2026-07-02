<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Your trusted source for the latest news and updates.')">
    <meta name="keywords" content="@yield('meta_keywords', 'news, breaking news, world news, technology, sports')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph Tags --}}
    @if(isset($ogTags))
        @foreach($ogTags as $key => $value)
            <meta property="{{ $key }}" content="{{ $value }}">
        @endforeach
    @endif

    {{-- Twitter Card Tags --}}
    @if(isset($twitterTags))
        @foreach($twitterTags as $key => $value)
            <meta name="{{ $key }}" content="{{ $value }}">
        @endforeach
    @endif

    {{-- Structured Data --}}
    @if(isset($structuredData))
        <script type="application/ld+json">{!! json_encode($structuredData) !!}</script>
    @endif
    @if(isset($breadcrumbs))
        <script type="application/ld+json">{!! json_encode($breadcrumbs) !!}</script>
    @endif
    <script type="application/ld+json">{!! json_encode($websiteStructuredData ?? [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => config('app.name'),
        'url' => config('app.url'),
    ]) !!}</script>

    {{-- RSS Feed --}}
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }}" href="{{ url('/sitemap.xml') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    {{-- Header Ad --}}
    @php $headerAd = \App\Models\AdPlacement::getByLocation('header'); @endphp
    @if($headerAd && $headerAd->code)
        <div class="bg-gray-100 py-2 text-center text-sm">{!! $headerAd->code !!}</div>
    @endif

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight">
                        <span class="text-blue-600">{{ config('app.name') }}</span>
                    </span>
                </a>

                {{-- Navigation --}}
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition">Home</a>
                    @php $navCategories = \App\Models\Category::where('is_active', true)->orderBy('order')->get(); @endphp
                    @foreach($navCategories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition">{{ $cat->name }}</a>
                    @endforeach
                </nav>

                {{-- Search --}}
                <div class="flex items-center gap-2 md:gap-4">
                    <form action="{{ url('/search') }}" method="GET" class="flex items-center flex-1 md:flex-initial">
                        <div class="relative w-full">
                            <input type="text" name="q" placeholder="Search news..." autocomplete="off"
                                class="search-autocomplete w-full md:w-48 lg:w-64 px-4 py-2 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="search-suggestions absolute top-full mt-2 z-50 hidden" style="width: 100%;">
                                <div class="suggestions-inner bg-white border border-gray-200 rounded-lg shadow-lg" style="max-height: 360px; overflow-y: auto;"></div>
                            </div>
                        </div>
                    </form>
                    <button type="button" id="mobile-menu-btn" class="md:hidden p-2 text-gray-600 hover:text-gray-900 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Overlay --}}
        <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity duration-300" style="opacity: 0;"></div>

        {{-- Mobile Menu Drawer --}}
        <div id="mobile-menu" class="fixed top-0 right-0 bottom-0 z-50 bg-white shadow-2xl transition-transform duration-300 md:hidden" style="width: 90%; max-width: 360px; transform: translateX(100%);">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <span class="text-lg font-bold text-gray-900">Menu</span>
                <button type="button" id="mobile-close-btn" class="p-1 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="px-5 py-4 overflow-y-auto h-full pb-20">
                <div class="space-y-1">
                    <a href="{{ url('/') }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">Home</a>
                    @foreach($navCategories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer Ad --}}
    @php $footerAd = \App\Models\AdPlacement::getByLocation('footer'); @endphp
    @if($footerAd && $footerAd->code)
        <div class="bg-gray-100 py-2 text-center text-sm">{!! $footerAd->code !!}</div>
    @endif

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">{{ config('app.name') }}</h3>
                    <p class="text-sm text-gray-400">Your trusted source for the latest news, analysis, and insights from around the world.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Categories</h4>
                    <ul class="space-y-2 text-sm">
                        @foreach($navCategories->take(6) as $cat)
                            <li><a href="{{ route('categories.show', $cat->slug) }}" class="hover:text-white transition">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ url('/') }}" class="hover:text-white transition">About Us</a></li>
                        <li><a href="{{ url('/') }}" class="hover:text-white transition">Contact</a></li>
                        <li><a href="{{ url('/') }}" class="hover:text-white transition">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Follow Us</h4>
                    <p class="text-sm text-gray-400">Stay connected through our social media channels for real-time updates.</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        (function() {
            var menuBtn = document.getElementById('mobile-menu-btn');
            var closeBtn = document.getElementById('mobile-close-btn');
            var menu = document.getElementById('mobile-menu');
            var overlay = document.getElementById('mobile-overlay');

            function openMenu() {
                menu.style.transform = 'translateX(0)';
                overlay.classList.remove('hidden');
                setTimeout(function() { overlay.style.opacity = '1'; }, 10);
                document.body.style.overflow = 'hidden';
            }

            function closeMenu() {
                menu.style.transform = 'translateX(100%)';
                overlay.style.opacity = '0';
                document.body.style.overflow = '';
                setTimeout(function() { overlay.classList.add('hidden'); }, 300);
            }

            menuBtn?.addEventListener('click', openMenu);
            closeBtn?.addEventListener('click', closeMenu);
            overlay?.addEventListener('click', closeMenu);
        })();

        // Search autocomplete
        (function() {
            function initAutocomplete(input) {
                var container = input.parentElement.querySelector('.search-suggestions');
                var inner = container ? container.querySelector('.suggestions-inner') : null;
                if (!container || !inner) return;

                var debounceTimer;

                input.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    var query = this.value.trim();

                    if (query.length < 2) {
                        container.classList.add('hidden');
                        return;
                    }

                    debounceTimer = setTimeout(function() {
                        fetch('/search/suggest?q=' + encodeURIComponent(query))
                            .then(function(r) { return r.json(); })
                            .then(function(data) {
                                if (!data.length) {
                                    container.classList.add('hidden');
                                    return;
                                }

                                var html = '';
                                data.forEach(function(article) {
                                    html += '<a href="/article/' + article.slug + '" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition">';
                                    if (article.featured_image) {
                                        html += '<img src="' + article.featured_image + '" alt="" class="w-10 h-10 rounded object-cover flex-shrink-0 mr-3">';
                                    } else {
                                        html += '<div class="w-10 h-10 rounded bg-blue-100 flex items-center justify-center flex-shrink-0 mr-3"><span class="text-blue-600 text-xs font-bold">N</span></div>';
                                    }
                                    html += '<div class="flex-1 min-w-0">';
                                    html += '<div class="text-sm font-medium text-gray-900 line-clamp-2">' + escapeHtml(article.title) + '</div>';
                                    html += '<div class="text-xs text-gray-400">' + article.category.name + ' · ' + timeAgo(article.published_at) + '</div>';
                                    html += '</div></a>';
                                });
                                inner.innerHTML = html;
                                container.classList.remove('hidden');
                            })
                            .catch(function() {
                                container.classList.add('hidden');
                            });
                    }, 300);
                });

                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target) && !container.contains(e.target)) {
                        container.classList.add('hidden');
                    }
                });

                input.addEventListener('focus', function() {
                    if (inner.querySelector('a')) {
                        container.classList.remove('hidden');
                    }
                });
            }

            document.querySelectorAll('.search-autocomplete').forEach(initAutocomplete);

            function escapeHtml(text) {
                var d = document.createElement('div');
                d.textContent = text;
                return d.innerHTML;
            }

            function timeAgo(dateStr) {
                var now = new Date();
                var d = new Date(dateStr);
                var diff = Math.floor((now - d) / 1000);
                if (diff < 60) return 'just now';
                if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
                if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
                return Math.floor(diff / 86400) + 'd ago';
            }
        })();
    </script>
</body>
</html>
