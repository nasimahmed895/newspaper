@extends('layouts.app')

@section('title', 'Unsubscribed — ' . config('app.name'))
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="text-6xl mb-4">✓</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">You've been unsubscribed</h1>
        <p class="text-gray-500 mb-8">You will no longer receive newsletter emails from {{ config('app.name') }}.</p>
        <a href="{{ route('home') }}" class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
            Back to Homepage
        </a>
    </div>
</div>
@endsection
