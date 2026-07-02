@extends('layouts.app')

@section('title', $article->seo_title ?? $article->title)
@section('meta_description', $article->seo_description ?? $article->excerpt)
@section('meta_keywords', $article->seo_keywords)

@section('content')
<article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a>
        <span>/</span>
        <a href="{{ route('categories.show', $article->category->slug) }}" class="hover:text-blue-600">{{ $article->category->name }}</a>
        <span>/</span>
        <span class="text-gray-900">{{ Str::limit($article->title, 50) }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Article --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Featured Image --}}
                @if($article->featured_image)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                </div>
                @endif

                <div class="p-6 lg:p-8">
                    {{-- Category & Meta --}}
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="text-xs font-bold text-blue-600 uppercase bg-blue-50 px-3 py-1 rounded-full">{{ $article->category->name }}</span>
                        <span class="text-sm text-gray-400">{{ $article->published_at?->format('F j, Y') }}</span>
                        @if($article->reading_time_minutes)
                            <span class="text-sm text-gray-400">{{ $article->reading_time_minutes }} min read</span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 leading-tight mb-4">
                        {{ $article->title }}
                    </h1>

                    {{-- Author --}}
                    @if($article->author)
                    <div class="flex items-center space-x-3 mb-6 pb-6 border-b border-gray-100">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {{ $article->author[0] }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $article->author }}</p>
                            <p class="text-xs text-gray-400">Staff Writer</p>
                        </div>
                    </div>
                    @endif

                    {{-- Article Body --}}
                    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-a:text-blue-600 prose-img:rounded-lg">
                        {!! $article->content !!}
                    </div>

                    {{-- In-Article Ad --}}
                    @php $inArticleAd = \App\Models\AdPlacement::getByLocation('in-article'); @endphp
                    @if($inArticleAd && $inArticleAd->code)
                        <div class="my-8 bg-gray-50 p-4 rounded-lg text-center text-sm">{!! $inArticleAd->code !!}</div>
                    @endif

                    {{-- Tags --}}
                    @if($article->seo_keywords)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $article->seo_keywords) as $keyword)
                                <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full">{{ trim($keyword) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Source --}}
                    @if($article->source)
                    <div class="mt-4 text-sm text-gray-400">
                        Source: @if($article->source_url)<a href="{{ $article->source_url }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline">@endif{{ $article->source }}@if($article->source_url)</a>@endif
                    </div>
                    @endif
                </div>
            </div>

            {{-- Related Articles --}}
            @if($relatedArticles->isNotEmpty())
            <section class="mt-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($relatedArticles as $related)
                    <a href="{{ route('articles.show', $related->slug) }}" class="group bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        @if($related->featured_image)
                        <div class="aspect-video overflow-hidden">
                            <img src="{{ $related->featured_image }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        @endif
                        <div class="p-4">
                            <span class="text-xs font-bold text-blue-600 uppercase">{{ $related->category->name }}</span>
                            <h3 class="text-sm font-bold text-gray-900 mt-1 group-hover:text-blue-600 transition">{{ $related->title }}</h3>
                            <span class="text-xs text-gray-400 mt-2 block">{{ $related->published_at?->diffForHumans() }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside>
            @php $sidebarTopAd = \App\Models\AdPlacement::getByLocation('sidebar-top'); @endphp
            @if($sidebarTopAd && $sidebarTopAd->code)
                <div class="bg-gray-100 p-4 rounded-lg text-center text-sm mb-8">{!! $sidebarTopAd->code !!}</div>
            @endif

            <div class="space-y-8 lg:sticky lg:top-16">
                {{-- Article Summary --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Article Summary</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $article->excerpt }}</p>
                </div>

                {{-- Share --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Share This Article</h3>
                    <div class="flex space-x-2">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('articles.show', $article->slug)) }}" target="_blank" class="flex-1 bg-black text-white text-center py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">X</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->slug)) }}" target="_blank" class="flex-1 bg-blue-600 text-white text-center py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">FB</a>
                    </div>
                </div>
            </div>

            @php $sidebarBottomAd = \App\Models\AdPlacement::getByLocation('sidebar-bottom'); @endphp
            @if($sidebarBottomAd && $sidebarBottomAd->code)
                <div class="bg-gray-100 p-4 rounded-lg text-center text-sm">{!! $sidebarBottomAd->code !!}</div>
            @endif
        </aside>
    </div>
</article>
@endsection
