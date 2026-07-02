@extends('layouts.app')

@section('title', '404 — Page Not Found')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="text-9xl font-black text-red-600 leading-none">404</div>
        <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-2">Page Not Found</h1>
        <p class="text-gray-500 mb-8">The article or page you're looking for has been removed, renamed, or doesn't exist.</p>
        <a href="{{ route('home') }}" class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
            Back to Homepage
        </a>
    </div>
</div>
@endsection
