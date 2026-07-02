@extends('layouts.app')
@section('title', 'About WorldPulse24 — Independent Global News Network')
@section('meta_description', 'WorldPulse24 is an independent global news network delivering breaking news and analysis across politics, business, technology, science, health, sports, and entertainment.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Hero --}}
    <div class="text-center mb-14">
        <h1 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4">
            About <span class="text-red-600">WorldPulse24</span>
        </h1>
        <p class="text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed">
            Independent journalism, powered by AI. Trusted by readers in every time zone.
        </p>
    </div>

    {{-- Mission --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-8 mb-8 shadow-sm">
        <h2 class="text-2xl font-black text-gray-900 mb-4">Our Mission</h2>
        <p class="text-gray-600 leading-relaxed mb-4">
            WorldPulse24 exists to deliver timely, accurate, and impartial news to a global audience. In a world of information overload, our mission is to cut through the noise — providing concise, trustworthy reporting on the stories that matter most.
        </p>
        <p class="text-gray-600 leading-relaxed">
            We believe access to quality journalism should not depend on geography, political affiliation, or economic status. WorldPulse24 is free, independent, and committed to serving readers across the English-speaking world with integrity.
        </p>
    </div>

    {{-- Values Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-red-50 rounded-2xl p-6 text-center">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-black text-gray-900 mb-2">Accuracy</h3>
            <p class="text-sm text-gray-500">Every article reviewed for factual accuracy. We correct errors promptly and transparently.</p>
        </div>
        <div class="bg-blue-50 rounded-2xl p-6 text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
            </div>
            <h3 class="font-black text-gray-900 mb-2">Independence</h3>
            <p class="text-sm text-gray-500">No political party, corporation, or government influences our editorial decisions.</p>
        </div>
        <div class="bg-green-50 rounded-2xl p-6 text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-black text-gray-900 mb-2">Transparency</h3>
            <p class="text-sm text-gray-500">We disclose AI assistance clearly. Our methods, sources, and processes are open.</p>
        </div>
    </div>

    {{-- AI Approach --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-black text-gray-900 mb-3">Our Approach to AI-Assisted Journalism</h2>
                <p class="text-gray-600 leading-relaxed mb-3">
                    WorldPulse24 uses artificial intelligence as a research and writing tool, not a replacement for editorial judgment. Our workflow combines WorldPulse24 research (drawing from Google Trends and real-time sources) with human editorial review to produce news that is both timely and accurate.
                </p>
                <p class="text-gray-600 leading-relaxed mb-3">
                    Every article published on WorldPulse24 is clearly labeled as AI-assisted and has been reviewed by a member of our editorial team before publication. We do not publish AI output verbatim without review.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    We believe this hybrid model represents the future of responsible news publishing — harnessing the speed and scale of AI while maintaining the accuracy, fairness, and editorial integrity that readers deserve.
                </p>
            </div>
        </div>
    </div>

    {{-- Coverage --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-8 mb-8">
        <h2 class="text-2xl font-black text-gray-900 mb-4">What We Cover</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @php
            $sections = [
                ['World News', '#EF4444'],
                ['Politics', '#DC2626'],
                ['Business', '#10B981'],
                ['Technology', '#3B82F6'],
                ['Science', '#8B5CF6'],
                ['Health', '#F59E0B'],
                ['Sports', '#06B6D4'],
                ['Entertainment', '#EC4899'],
            ];
            @endphp
            @foreach($sections as [$name, $color])
            <div class="flex items-center gap-2 text-sm font-semibold text-gray-700 bg-gray-50 px-3 py-2.5 rounded-xl">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $color }}"></span>
                {{ $name }}
            </div>
            @endforeach
        </div>
        <p class="text-sm text-gray-500 mt-4">Focused on English-speaking audiences in the United States, United Kingdom, Canada, and Australia, with coverage of global events affecting these communities.</p>
    </div>

    {{-- Contact CTA --}}
    <div class="text-center bg-slate-900 rounded-2xl p-8 text-white">
        <h2 class="text-2xl font-black mb-3">Questions or Feedback?</h2>
        <p class="text-gray-300 mb-6">We take reader feedback seriously. Reach out for corrections, tips, or general enquiries.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('contact') }}" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition">Contact Us</a>
            <a href="{{ route('editorial-policy') }}" class="px-6 py-3 bg-slate-700 text-white font-bold rounded-xl hover:bg-slate-600 transition">Editorial Policy</a>
        </div>
    </div>

</div>
@endsection
