@extends('layouts.app')

@section('title', 'Search: ' . $query . ' - ' . config('app.name'))
@section('meta_description', 'Search results for ' . $query)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Search Results</h1>
        <p class="mt-2 text-gray-600">Showing results for "{{ $query }}"</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            @if($articles->isNotEmpty())
            <div class="space-y-4">
                @foreach($articles as $article)
                <article class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="text-xs font-bold text-blue-600 uppercase">{{ $article->category->name }}</span>
                        <span class="text-xs text-gray-400">{{ $article->published_at?->diffForHumans() }}</span>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-2">
                        <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-blue-600 transition">{{ $article->title }}</a>
                    </h2>
                    <p class="text-gray-600 text-sm line-clamp-2">{{ $article->excerpt }}</p>
                </article>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
            @else
            <div class="text-center py-12 bg-white rounded-lg border border-gray-100">
                <p class="text-gray-500 text-lg">No results found for "{{ $query }}"</p>
                <p class="text-gray-400 text-sm mt-2">Try different keywords or browse categories.</p>
            </div>
            @endif
        </div>

        <aside class="space-y-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Search</h3>
                <form action="{{ url('/search') }}" method="GET">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search news..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="mt-2 w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">Search</button>
                </form>
            </div>
        </aside>
    </div>
</div>
@endsection
