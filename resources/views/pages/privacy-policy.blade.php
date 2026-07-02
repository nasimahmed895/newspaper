@extends('layouts.app')
@section('title', 'Privacy Policy — WorldPulse24')
@section('meta_description', 'WorldPulse24 privacy policy explaining how we collect, use, and protect your personal data in compliance with GDPR and CCPA.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-10">
        <h1 class="text-4xl font-black text-gray-900 mb-3">Privacy Policy</h1>
        <p class="text-gray-500">Effective date: January 1, 2025 &nbsp;·&nbsp; Last updated: {{ now()->format('F j, Y') }}</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8">
        <p class="text-blue-800 text-sm leading-relaxed">
            <strong>Summary:</strong> WorldPulse24 collects minimal data necessary to operate our news service. We do not sell your personal information to third parties. This policy explains what data we collect, how we use it, and your rights.
        </p>
    </div>

    <div class="space-y-6">
        @php
        $sections = [
            ['1. Information We Collect', [
                '<strong>Usage Data:</strong> When you visit WorldPulse24, we automatically collect basic technical information including your IP address (anonymized), browser type, operating system, referring URL, pages visited, and time on site. This data is used to analyze site performance and improve user experience.',
                '<strong>Newsletter Email:</strong> If you subscribe to our newsletter, we collect your email address solely to send you news updates. You can unsubscribe at any time using the link in any email.',
                '<strong>Cookies:</strong> We use essential cookies for site functionality and analytics cookies (anonymized) to understand how readers use our site. We do not use tracking cookies for advertising without your explicit consent.',
                '<strong>Contact Form Data:</strong> If you contact us, we collect the information you provide (name, email, message) to respond to your enquiry.',
            ]],
            ['2. How We Use Your Information', [
                'To deliver and improve our news content and website functionality.',
                'To analyze traffic patterns and optimize site performance (Core Web Vitals, page load speeds).',
                'To send newsletter subscribers the email updates they have requested.',
                'To respond to your enquiries and feedback.',
                'To comply with legal obligations.',
                'We do not use your data for automated decision-making or profiling.',
            ]],
            ['3. Advertising and Third Parties', [
                'WorldPulse24 may display third-party advertisements (such as Google AdSense). These third parties may use cookies to serve relevant ads. We do not control their data practices.',
                'We do not sell, rent, or trade your personal information to any third party for marketing purposes.',
                'We use anonymized analytics data (such as Google Analytics 4 with IP anonymization) to understand audience behavior.',
            ]],
            ['4. Data Retention', [
                'Newsletter email addresses are retained until you unsubscribe.',
                'Contact form data is retained for up to 12 months.',
                'Analytics data is retained in anonymized, aggregated form.',
            ]],
            ['5. Your Rights (GDPR & CCPA)', [
                '<strong>Access:</strong> You may request a copy of the personal data we hold about you.',
                '<strong>Correction:</strong> You may request correction of inaccurate personal data.',
                '<strong>Deletion:</strong> You may request deletion of your personal data ("right to be forgotten").',
                '<strong>Objection:</strong> You may object to processing of your personal data for certain purposes.',
                '<strong>Portability:</strong> You may request your data in a portable format.',
                'To exercise any of these rights, contact us at <a href="/contact" class="text-red-600 hover:underline font-semibold">our contact page</a>. We will respond within 30 days.',
            ]],
            ['6. Security', [
                'WorldPulse24 implements industry-standard security measures including HTTPS encryption, security headers (CSP, HSTS, X-Frame-Options), and regular security audits.',
                'No data transmission over the internet is 100% secure. While we protect your information to the best of our ability, we cannot guarantee absolute security.',
            ]],
            ['7. Children\'s Privacy', [
                'WorldPulse24 is not directed to children under 13. We do not knowingly collect personal information from children. If you believe we have collected data from a child, please contact us immediately.',
            ]],
            ['8. Changes to This Policy', [
                'We may update this Privacy Policy periodically. Significant changes will be noted with an updated "Last updated" date at the top of this page. Continued use of WorldPulse24 after changes constitutes acceptance of the updated policy.',
            ]],
        ];
        @endphp

        @foreach($sections as [$title, $items])
        <div class="bg-white rounded-2xl border border-gray-100 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-4">{{ $title }}</h2>
            <ul class="space-y-3">
                @foreach($items as $item)
                <li class="flex items-start gap-3 text-gray-600 text-sm leading-relaxed">
                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full flex-shrink-0 mt-2"></span>
                    <span>{!! $item !!}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach

        <div class="bg-slate-50 rounded-2xl border border-gray-200 p-8">
            <h2 class="text-xl font-black text-gray-900 mb-3">Contact Us About Privacy</h2>
            <p class="text-gray-600 mb-5">For privacy-related enquiries, data requests, or to exercise your rights:</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition">
                Privacy Enquiry →
            </a>
        </div>
    </div>

</div>
@endsection
