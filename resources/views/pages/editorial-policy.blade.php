@extends('layouts.app')
@section('title', 'Editorial Policy — WorldPulse24')
@section('meta_description', 'WorldPulse24 editorial policy covering our standards for accuracy, fairness, AI transparency, corrections, and journalistic independence.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-10">
        <h1 class="text-4xl font-black text-gray-900 mb-3">Editorial Policy</h1>
        <p class="text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>
    </div>

    <div class="space-y-8">

        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-4">1. Our Editorial Independence</h2>
            <p class="text-gray-600 leading-relaxed mb-3">
                WorldPulse24 operates with full editorial independence. Our coverage is not influenced by advertisers, political parties, governments, or any external stakeholder. All editorial decisions — what to cover, how to cover it, and when — are made solely on journalistic grounds.
            </p>
            <p class="text-gray-600 leading-relaxed">
                Revenue from advertising supports our operations but has no influence over the content we produce. Advertisers cannot request, review, or veto editorial content.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-8" id="ai">
            <h2 class="text-xl font-black text-gray-900 mb-4">2. AI-Assisted Reporting — Our Standards</h2>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-5">
                <p class="text-sm font-bold text-amber-800">Transparency Commitment</p>
                <p class="text-sm text-amber-700 mt-1">All content produced with AI assistance is labeled "AI-Assisted Reporting" at the top of the article. We never misrepresent AI-generated content as purely human-written journalism.</p>
            </div>
            <p class="text-gray-600 leading-relaxed mb-3">
                WorldPulse24 uses AI tools to assist with research, fact-gathering, and initial drafting. Our AI system sources topics from real-time trending data (Google Trends US, UK, CA) and draws on publicly available information.
            </p>
            <p class="text-gray-600 leading-relaxed mb-3">
                <strong>Human review is mandatory.</strong> No AI-generated article is published without editorial review by a staff member who verifies key claims, checks for bias, ensures balanced presentation, and confirms the article meets our accuracy standards.
            </p>
            <p class="text-gray-600 leading-relaxed">
                We acknowledge that AI systems can produce errors, outdated information, or subtly biased framing. Our review process is designed to catch and correct such issues before publication. If you believe an article contains an error, please <a href="{{ route('contact') }}" class="text-red-600 hover:underline font-semibold">contact us immediately</a>.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-4">3. Accuracy and Fact-Checking</h2>
            <ul class="space-y-3">
                @foreach([
                    'All factual claims in articles must be supportable by credible, publicly available sources.',
                    'Statistics and data points are attributed to authoritative sources where possible.',
                    'Expert quotes are clearly attributed. Unnamed sources are not used without compelling editorial justification.',
                    'We do not publish speculation as fact. Unconfirmed reports are clearly labeled.',
                    'We strive to contact relevant parties for comment before publication where feasible.',
                ] as $item)
                <li class="flex items-start gap-3 text-gray-600 text-sm">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-4">4. Fairness and Balance</h2>
            <p class="text-gray-600 leading-relaxed mb-3">
                WorldPulse24 strives to present multiple perspectives on complex or politically sensitive topics. We do not editorialize in news reporting. Opinion content, if published, is clearly labeled as such.
            </p>
            <p class="text-gray-600 leading-relaxed">
                We are committed to covering all communities, regions, and viewpoints fairly. If you believe our coverage has been unfair or one-sided, we welcome your feedback via our <a href="{{ route('contact') }}" class="text-red-600 hover:underline">contact form</a>.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-4">5. Corrections Policy</h2>
            <p class="text-gray-600 leading-relaxed mb-3">
                WorldPulse24 takes errors seriously. When we make a factual error, we correct it promptly and transparently. Corrections are noted at the top of the affected article with a brief explanation of what was changed and why.
            </p>
            <p class="text-gray-600 leading-relaxed">
                To submit a correction request, please use our <a href="{{ route('contact') }}" class="text-red-600 hover:underline font-semibold">Contact page</a> with the article URL, the specific error, and supporting evidence. We aim to respond to correction requests within 48 hours.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-4">6. Conflicts of Interest</h2>
            <p class="text-gray-600 leading-relaxed">
                Staff members are required to disclose any potential conflicts of interest in relation to content they produce or oversee. WorldPulse24 does not accept payment for editorial coverage (native advertising or sponsored content, if published, is clearly labeled "Sponsored" and separated from editorial content).
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-4">7. Images and Media</h2>
            <p class="text-gray-600 leading-relaxed mb-3">
                WorldPulse24 sources images from licensed stock providers (Unsplash) and AI generation tools (with disclosure). We do not alter images in ways that change their factual meaning.
            </p>
            <p class="text-gray-600 leading-relaxed">
                AI-generated images are labeled "AI Generated" in image credits. Unsplash images credit the photographer and source.
            </p>
        </div>

        <div class="bg-slate-50 rounded-2xl border border-gray-200 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-3">Contact the Editorial Team</h2>
            <p class="text-gray-600 mb-5">For editorial inquiries, corrections, tips, or feedback on our coverage:</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Contact Editorial Team
            </a>
        </div>

    </div>
</div>
@endsection
