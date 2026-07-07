<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Primary SEO --}}
    <title>@yield('title', config('app.name') . ' — Breaking News, World Events & Analysis')</title>
    <meta name="description" content="@yield('meta_description', 'WorldPulse24 delivers breaking news, in-depth analysis, and trusted reporting from around the world. Stay informed with WorldPulse24 journalism.')">
    <meta name="keywords" content="@yield('meta_keywords', 'breaking news, world news, politics, business, technology, science, health, sports')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#ffffff">

    {{-- Google News --}}
    <meta name="news_keywords" content="@yield('meta_keywords', 'breaking news, world news, global events')">
    <meta name="syndication-source" content="{{ url()->current() }}">
    <meta name="original-source" content="{{ url()->current() }}">

    {{-- Open Graph --}}
    @if(isset($ogTags))
        @foreach($ogTags as $key => $value)
            <meta property="{{ $key }}" content="{{ $value }}">
        @endforeach
    @else
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="@yield('title', config('app.name') . ' — Breaking News & World Events')">
        <meta property="og:description" content="@yield('meta_description', 'WorldPulse24 delivers breaking news and trusted reporting from around the world.')">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:locale" content="en_US">
    @endif

    {{-- Twitter Card --}}
    @if(isset($twitterTags))
        @foreach($twitterTags as $key => $value)
            <meta name="{{ $key }}" content="{{ $value }}">
        @endforeach
    @else
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@WorldPulse24">
        <meta name="twitter:title" content="@yield('title', config('app.name'))">
        <meta name="twitter:description" content="@yield('meta_description', 'WorldPulse24 — Breaking news and world events.')">
    @endif

    {{-- Structured Data --}}
    @if(isset($structuredData))
        <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @if(isset($breadcrumbs))
        <script type="application/ld+json">{!! json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    {{-- Website + NewsMediaOrganization structured data --}}
    @php
    $siteJsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            $websiteStructuredData ?? [
                '@type' => 'WebSite',
                'name' => config('app.name'),
                'url' => config('app.url'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => ['@type' => 'EntryPoint', 'urlTemplate' => url('/search?q={search_term_string}')],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type' => 'NewsMediaOrganization',
                'name' => 'WorldPulse24',
                'url' => config('app.url'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('/images/logo.png'),
                    'width' => 200,
                    'height' => 60,
                ],
                'foundingDate' => '2024',
                'description' => 'WorldPulse24 is an independent global news network delivering breaking news, analysis, and in-depth reporting across politics, business, technology, science, health, sports, and entertainment.',
                'masthead' => url('/about'),
                'ethicsPolicy' => url('/editorial-policy'),
                'correctionsPolicy' => url('/contact'),
                'ownershipFundingInfo' => url('/about'),
                'actionableFeedbackPolicy' => url('/contact'),
                'diversityPolicy' => url('/editorial-policy'),
                'unnamedSourcesPolicy' => url('/editorial-policy'),
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <script type="application/ld+json">{!! $siteJsonLd !!}</script>

    {{-- RSS / Sitemap --}}
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} RSS Feed" href="{{ url('/sitemap.xml') }}">

    {{-- Performance: preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-marquee { display: inline-block; animation: marquee 35s linear infinite; }
        @keyframes marquee { 0% { transform: translateX(100vw); } 100% { transform: translateX(-100%); } }
        .category-pill { transition: all .15s ease; }
        .category-pill:hover { opacity: .85; }
        .article-card-img { transition: transform .4s ease; }
        .article-card:hover .article-card-img { transform: scale(1.04); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .dd-open .dd-menu { display: block !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    {{-- Reading progress bar (article pages only) --}}
    @hasSection('reading_progress')
    <div class="fixed top-0 left-0 w-full h-[3px] bg-gray-200 z-[70]" id="progress-track">
        <div id="reading-progress" class="h-full bg-red-600 transition-[width] duration-100" style="width:0%"></div>
    </div>
    @endif

    {{-- TOP STRIP --}}
    <div class="bg-slate-900 text-gray-300 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-9">
            <span class="hidden sm:block font-medium tracking-wide">
                {{ now()->format('l, F j, Y') }}
            </span>
            <span class="text-center text-gray-400 text-[11px] tracking-widest font-semibold uppercase">
                Independent &bull; Trusted &bull; Global
            </span>
            <div class="flex items-center space-x-3">
                <a href="/about" class="hover:text-white transition text-[11px]">About</a>
                <span class="text-gray-600">|</span>
                <a href="/contact" class="hover:text-white transition text-[11px]">Contact</a>
                <span class="text-gray-600">|</span>
                <a href="/editorial-policy" class="hover:text-white transition text-[11px]">Editorial</a>
            </div>
        </div>
    </div>

    {{-- HEADER --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        {{-- Header Ad --}}
        @php $headerAd = \App\Models\AdPlacement::getByLocation('header'); @endphp
        @if($headerAd && $headerAd->code)
            <div class="bg-gray-50 border-b border-gray-100 py-1 text-center text-xs">{!! $headerAd->code !!}</div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center space-x-2 flex-shrink-0" aria-label="WorldPulse24 Home">
                    <div class="relative w-9 h-9">
                        <svg viewBox="0 0 36 36" fill="none" class="w-9 h-9">
                            <circle cx="18" cy="18" r="17" fill="#DC2626"/>
                            <circle cx="18" cy="18" r="13" fill="none" stroke="white" stroke-width="1.2"/>
                            <ellipse cx="18" cy="18" rx="6" ry="13" fill="none" stroke="white" stroke-width="1.2"/>
                            <line x1="5" y1="18" x2="31" y2="18" stroke="white" stroke-width="1.2"/>
                            <line x1="5" y1="13" x2="31" y2="13" stroke="white" stroke-width=".8" opacity=".6"/>
                            <line x1="5" y1="23" x2="31" y2="23" stroke="white" stroke-width=".8" opacity=".6"/>
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="font-black text-[22px] tracking-tight leading-none">
                            <span class="text-slate-900">World</span><span class="text-red-600">Pulse</span><span class="text-slate-500 font-semibold">24</span>
                        </span>
                        <span class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase mt-0.5">Global News Network</span>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                @php $navCategories = \App\Models\Category::where('is_active', true)->orderBy('order')->get(); @endphp
                <nav class="hidden lg:flex items-center space-x-1" aria-label="Main navigation">
                    <a href="{{ url('/') }}"
                       class="px-3 py-2 text-sm font-semibold rounded-md transition whitespace-nowrap {{ request()->routeIs('home') ? 'text-red-700 bg-red-50' : 'text-gray-700 hover:text-red-600 hover:bg-red-50' }}">Home</a>
                    @foreach($navCategories->take(5) as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}"
                           class="px-3 py-2 text-sm font-medium rounded-md transition whitespace-nowrap {{ request()->routeIs('categories.show') && request()->route('slug') === $cat->slug ? 'text-red-700 bg-red-50' : 'text-gray-600 hover:text-red-600 hover:bg-red-50' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                    @if($navCategories->count() > 5)
                    <div class="relative" id="more-dropdown">
                        <button onclick="document.getElementById('more-dropdown').classList.toggle('dd-open')" type="button" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-md transition whitespace-nowrap flex items-center gap-1">
                            More
                            <svg class="w-3.5 h-3.5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="dd-menu absolute top-full right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-xl py-2 min-w-[180px] z-50" style="display:none">
                            @foreach($navCategories->skip(5) as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}"
                               onclick="document.getElementById('more-dropdown').classList.remove('dd-open')"
                               class="block px-4 py-2 text-sm {{ request()->routeIs('categories.show') && request()->route('slug') === $cat->slug ? 'text-red-700 bg-red-50 font-semibold' : 'text-gray-700 hover:text-red-600 hover:bg-red-50' }}">
                                {{ $cat->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </nav>

                {{-- Search + Mobile toggle --}}
                <div class="flex items-center gap-3">
                    <form action="{{ url('/search') }}" method="GET" class="relative hidden md:block">
                        <input type="text" name="q" placeholder="Search…" autocomplete="off"
                            class="search-autocomplete w-44 lg:w-56 pl-9 pr-4 py-2 text-sm bg-gray-100 border border-transparent rounded-full focus:outline-none focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 transition">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <div class="search-suggestions absolute top-full mt-2 z-50 hidden w-80 right-0">
                            <div class="suggestions-inner bg-white border border-gray-200 rounded-xl shadow-xl max-h-96 overflow-y-auto"></div>
                        </div>
                    </form>
                    <button type="button" id="mobile-menu-btn" class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg" aria-label="Open menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile Menu Overlay --}}
    <div id="mobile-overlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden backdrop-blur-sm" style="opacity:0;"></div>

    {{-- Mobile Drawer --}}
    <div id="mobile-menu" class="fixed top-0 left-0 bottom-0 z-50 bg-white shadow-2xl lg:hidden flex flex-col" style="width:90%;max-width:360px;transform:translateX(-100%);transition:transform .3s ease;">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <a href="/" class="font-black text-xl">
                <span class="text-slate-900">World</span><span class="text-red-600">Pulse</span><span class="text-slate-500 font-semibold">24</span>
            </a>
            <button type="button" id="mobile-close-btn" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg" aria-label="Close menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto">
            <form action="{{ url('/search') }}" method="GET" class="px-5 py-3 border-b border-gray-100">
                <div class="relative">
                    <input type="text" name="q" placeholder="Search WorldPulse24…"
                        class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-200">
                    <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
            <nav class="px-3 py-4">
                <a href="{{ url('/') }}"
                   class="flex items-center px-4 py-3 text-base rounded-xl transition {{ request()->routeIs('home') ? 'text-red-700 bg-red-50 font-bold' : 'font-semibold text-gray-900 hover:text-red-600 hover:bg-red-50' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('home') ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                @foreach($navCategories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                   class="flex items-center px-4 py-3 text-base rounded-xl transition {{ request()->routeIs('categories.show') && request()->route('slug') === $cat->slug ? 'text-red-700 bg-red-50 font-bold' : 'font-medium text-gray-700 hover:text-red-600 hover:bg-red-50' }}">
                    <span class="w-2 h-2 rounded-full mr-3 flex-shrink-0" style="background-color: {{ $cat->color ?? '#6B7280' }}"></span>
                    {{ $cat->name }}
                </a>
                @endforeach
            </nav>
            <div class="px-5 py-4 border-t border-gray-100 space-y-1">
                <a href="/about" class="block px-4 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('about') ? 'text-red-700 bg-red-50 font-bold' : 'text-gray-600 hover:text-red-600 hover:bg-red-50' }}">About Us</a>
                <a href="/editorial-policy" class="block px-4 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('editorial-policy') ? 'text-red-700 bg-red-50 font-bold' : 'text-gray-600 hover:text-red-600 hover:bg-red-50' }}">Editorial Policy</a>
                <a href="/contact" class="block px-4 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('contact') ? 'text-red-700 bg-red-50 font-bold' : 'text-gray-600 hover:text-red-600 hover:bg-red-50' }}">Contact</a>
                <a href="/privacy-policy" class="block px-4 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('privacy-policy') ? 'text-red-700 bg-red-50 font-bold' : 'text-gray-600 hover:text-red-600 hover:bg-red-50' }}">Privacy Policy</a>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-gray-300 mt-16">

        {{-- Newsletter Band --}}
        <div class="bg-red-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h3 class="text-2xl font-black text-white">Stay Ahead of the News</h3>
                        <p class="text-red-100 text-sm mt-1">Join readers getting the world's top stories, twice daily. Free.</p>
                    </div>
                    <form action="{{ route('newsletter') }}" method="POST" class="flex w-full md:w-auto gap-2">
                        @csrf
                        <input type="email" name="email" placeholder="Enter your email address" required
                            class="flex-1 md:w-72 px-4 py-3 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-white placeholder-gray-400">
                        <button type="submit"
                            class="px-6 py-3 bg-white text-red-600 font-bold text-sm rounded-lg hover:bg-red-50 transition whitespace-nowrap">
                            Subscribe Free
                        </button>
                    </form>
                </div>
                @if(session('newsletter_success'))
                    <p class="mt-3 text-white font-medium text-sm">✓ {{ session('newsletter_success') }}</p>
                @endif
            </div>
        </div>

        {{-- Footer Links --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <svg viewBox="0 0 36 36" fill="none" class="w-8 h-8 flex-shrink-0">
                            <circle cx="18" cy="18" r="17" fill="#DC2626"/>
                            <circle cx="18" cy="18" r="13" fill="none" stroke="white" stroke-width="1.2"/>
                            <ellipse cx="18" cy="18" rx="6" ry="13" fill="none" stroke="white" stroke-width="1.2"/>
                            <line x1="5" y1="18" x2="31" y2="18" stroke="white" stroke-width="1.2"/>
                        </svg>
                        <span class="font-black text-xl text-white">World<span class="text-red-500">Pulse</span><span class="font-semibold text-gray-400">24</span></span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Independent global news network delivering breaking news, analysis, and in-depth reporting from every corner of the world.
                    </p>
                    <div class="flex items-center space-x-3 mt-5">
                        <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter/X" class="w-8 h-8 bg-slate-700 hover:bg-red-600 rounded-full flex items-center justify-center transition">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.736-8.844L1.254 2.25H8.08l4.256 5.65 5.908-5.65zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook" class="w-8 h-8 bg-slate-700 hover:bg-red-600 rounded-full flex items-center justify-center transition">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn" class="w-8 h-8 bg-slate-700 hover:bg-red-600 rounded-full flex items-center justify-center transition">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Categories --}}
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-5">News Sections</h4>
                    <ul class="space-y-2.5">
                        @foreach($navCategories as $cat)
                        <li>
                            <a href="{{ route('categories.show', $cat->slug) }}" class="text-sm transition flex items-center {{ request()->routeIs('categories.show') && request()->route('slug') === $cat->slug ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-2.5 flex-shrink-0" style="background-color: {{ $cat->color ?? '#6B7280' }}"></span>
                                {{ $cat->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-5">Company</h4>
                    <ul class="space-y-2.5">
                        <li><a href="/about" class="text-sm transition {{ request()->routeIs('about') ? 'text-white' : 'text-gray-400 hover:text-white' }}">About WorldPulse24</a></li>
                        <li><a href="/editorial-policy" class="text-sm transition {{ request()->routeIs('editorial-policy') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Editorial Policy</a></li>
                        <li><a href="/contact" class="text-sm transition {{ request()->routeIs('contact') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Contact Us</a></li>
                        <li><a href="/contact" class="text-sm text-gray-400 hover:text-white transition">Submit a Correction</a></li>
                        <li><a href="/privacy-policy" class="text-sm transition {{ request()->routeIs('privacy-policy') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Privacy Policy</a></li>
                    </ul>
                </div>

                {{-- Trust & Transparency --}}
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-5">Our Standards</h4>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-red-600/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold">Accuracy First</p>
                                <p class="text-gray-500 text-xs mt-0.5">Every article reviewed before publication</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-red-600/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold">AI Transparency</p>
                                <p class="text-gray-500 text-xs mt-0.5">AI-assisted content clearly disclosed</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-red-600/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold">Independent</p>
                                <p class="text-gray-500 text-xs mt-0.5">No political or corporate bias</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-gray-500">
                        &copy; {{ date('Y') }} WorldPulse24. All rights reserved. Content produced with AI research assistance and human editorial review.
                    </p>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <a href="/privacy-policy" class="hover:text-gray-300 transition">Privacy</a>
                        <a href="/editorial-policy" class="hover:text-gray-300 transition">Editorial</a>
                        <a href="/contact" class="hover:text-gray-300 transition">Corrections</a>
                        <a href="/sitemap.xml" class="hover:text-gray-300 transition">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
    // ─── Mobile Drawer ───────────────────────────────────────────────
    (function () {
        var btn = document.getElementById('mobile-menu-btn');
        var closeBtn = document.getElementById('mobile-close-btn');
        var menu = document.getElementById('mobile-menu');
        var overlay = document.getElementById('mobile-overlay');

        function openMenu() {
            menu.style.transform = 'translateX(0)';
            overlay.classList.remove('hidden');
            setTimeout(function () { overlay.style.opacity = '1'; }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            menu.style.transform = 'translateX(-100%)';
            overlay.style.opacity = '0';
            document.body.style.overflow = '';
            setTimeout(function () { overlay.classList.add('hidden'); }, 300);
        }

        btn && btn.addEventListener('click', openMenu);
        closeBtn && closeBtn.addEventListener('click', closeMenu);
        overlay && overlay.addEventListener('click', closeMenu);
    })();

    // ─── Search Autocomplete ─────────────────────────────────────────
    (function () {
        function initAutocomplete(input) {
            var container = input.closest('.relative').querySelector('.search-suggestions');
            var inner = container ? container.querySelector('.suggestions-inner') : null;
            if (!container || !inner) return;
            var timer;

            input.addEventListener('input', function () {
                clearTimeout(timer);
                var q = this.value.trim();
                if (q.length < 2) { container.classList.add('hidden'); return; }
                timer = setTimeout(function () {
                    fetch('/search/suggest?q=' + encodeURIComponent(q))
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            if (!data.length) { container.classList.add('hidden'); return; }
                            var html = data.map(function (a) {
                                return '<a href="/article/' + a.slug + '" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition gap-3">'
                                    + (a.featured_image ? '<img src="' + a.featured_image + '" alt="" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">' : '<div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600 font-bold text-sm">W</div>')
                                    + '<div class="flex-1 min-w-0"><p class="text-sm font-semibold text-gray-900 line-clamp-2">' + esc(a.title) + '</p><p class="text-xs text-gray-400 mt-0.5">' + a.category.name + ' &bull; ' + timeAgo(a.published_at) + '</p></div></a>';
                            }).join('');
                            inner.innerHTML = html;
                            container.classList.remove('hidden');
                        }).catch(function () { container.classList.add('hidden'); });
                }, 280);
            });

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !container.contains(e.target)) container.classList.add('hidden');
            });
        }

        document.querySelectorAll('.search-autocomplete').forEach(initAutocomplete);

        function esc(t) { var d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
        function timeAgo(s) {
            var d = Math.floor((Date.now() - new Date(s)) / 1000);
            if (d < 60) return 'just now';
            if (d < 3600) return Math.floor(d / 60) + 'm ago';
            if (d < 86400) return Math.floor(d / 3600) + 'h ago';
            return Math.floor(d / 86400) + 'd ago';
        }
    })();

    // ─── Reading Progress ────────────────────────────────────────────
    (function () {
        var bar = document.getElementById('reading-progress');
        if (!bar) return;
        var body = document.getElementById('article-body');
        window.addEventListener('scroll', function () {
            if (!body) return;
            var rect = body.getBoundingClientRect();
            var pct = Math.min(100, Math.max(0, (-rect.top / body.offsetHeight) * 100));
            bar.style.width = pct + '%';
        }, { passive: true });
    })();

    // ─── More Dropdown ────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var dd = document.getElementById('more-dropdown');
        if (!dd) return;
        if (dd.classList.contains('dd-open') && !dd.contains(e.target)) {
            dd.classList.remove('dd-open');
        }
    });
    </script>
</body>
</html>
