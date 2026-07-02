@extends('layouts.app')

@section('title', $category->name . ' - ' . config('app.name'))
@section('meta_description', $category->description ?? 'Latest news in ' . $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Category Header --}}
    <div class="mb-8">
        <div class="flex items-center space-x-3">
            @if($category->color)
            <div class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></div>
            @endif
            <h1 class="text-3xl font-extrabold text-gray-900">{{ $category->name }}</h1>
        </div>
        @if($category->description)
            <p class="mt-2 text-gray-600">{{ $category->description }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Articles Grid --}}
        <div class="lg:col-span-2">
            @if($articles->isNotEmpty())
            <div class="space-y-6">
                @foreach($articles as $article)
                <article class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row">
                        @if($article->featured_image)
                        <div class="sm:w-48 sm:flex-shrink-0">
                            <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-48 sm:h-full object-cover">
                        </div>
                        @endif
                        <div class="p-5 flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="text-xs text-gray-400">{{ $article->published_at?->diffForHumans() }}</span>
                                <span class="text-xs text-gray-300">·</span>
                                <span class="text-xs text-gray-400">{{ $article->reading_time_minutes }} min read</span>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 mb-2">
                                <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-blue-600 transition">{{ $article->title }}</a>
                            </h2>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $article->excerpt }}</p>
                            @if($article->is_trending)
                            <span class="inline-block mt-2 text-xs text-red-500 font-semibold">🔥 Trending</span>
                            @endif
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
            @else
            <div class="text-center py-12 bg-white rounded-lg border border-gray-100">
                <p class="text-gray-500 text-lg">No articles found in this category.</p>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-8">
            @php $sidebarTopAd = \App\Models\AdPlacement::getByLocation('sidebar-top'); @endphp
            @if($sidebarTopAd && $sidebarTopAd->code)
                <div class="bg-gray-100 p-4 rounded-lg text-center text-sm">{!! $sidebarTopAd->code !!}</div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                <h3 class="text-lg font-bold text-gray-900 mb-4">All Categories</h3>
                <div class="space-y-2">
                    @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50 transition {{ $cat->id === $category->id ? 'bg-blue-50 text-blue-600' : '' }}">
                        <span class="text-sm font-medium">{{ $cat->name }}</span>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $cat->publishedArticles()->count() }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
