@extends('layouts.app')
@section('title', 'Contact WorldPulse24 — Tips, Corrections & Feedback')
@section('meta_description', 'Contact the WorldPulse24 editorial team for corrections, news tips, partnership enquiries, or general feedback about our news coverage.')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-gray-900 mb-3">Contact Us</h1>
        <p class="text-gray-500 max-w-xl mx-auto">We read every message. Corrections, tips, and feedback make our journalism better.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Contact Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 p-8">

                @if(session('contact_success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-green-800 font-semibold">{{ session('contact_success') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300 transition @error('name') border-red-400 @enderror">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300 transition @error('email') border-red-400 @enderror">
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-bold text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                        <select id="subject" name="subject" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300 bg-white transition">
                            <option value="">— Select a subject —</option>
                            <option value="correction" {{ old('subject') === 'correction' ? 'selected' : '' }}>Correction Request</option>
                            <option value="tip" {{ old('subject') === 'tip' ? 'selected' : '' }}>News Tip</option>
                            <option value="feedback" {{ old('subject') === 'feedback' ? 'selected' : '' }}>General Feedback</option>
                            <option value="privacy" {{ old('subject') === 'privacy' ? 'selected' : '' }}>Privacy Enquiry</option>
                            <option value="advertising" {{ old('subject') === 'advertising' ? 'selected' : '' }}>Advertising</option>
                            <option value="other" {{ old('subject') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="article_url" class="block text-sm font-bold text-gray-700 mb-2">
                            Article URL <span class="text-gray-400 font-normal">(if reporting a correction)</span>
                        </label>
                        <input type="url" id="article_url" name="article_url" value="{{ old('article_url') }}" placeholder="https://worldpulse24.news/article/..."
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300 transition">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-bold text-gray-700 mb-2">Message <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="6" required
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300 transition resize-none @error('message') border-red-400 @enderror"
                                  placeholder="Describe your correction, tip, or feedback in detail…">{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full px-6 py-3.5 bg-red-600 text-white font-black text-base rounded-xl hover:bg-red-700 transition">
                        Send Message
                    </button>
                </form>
            </div>
        </div>

        {{-- Info Panel --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="font-black text-gray-900 mb-4">Response Time</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Corrections</p>
                            <p class="text-xs text-gray-500">Within 48 hours</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">General Enquiries</p>
                            <p class="text-xs text-gray-500">Within 5 business days</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">News Tips</p>
                            <p class="text-xs text-gray-500">Reviewed within 24 hours</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-2xl p-6 text-white">
                <h3 class="font-black mb-3">For Urgent Corrections</h3>
                <p class="text-sm text-gray-300 mb-4">If an article contains a serious factual error that could cause harm, include "URGENT CORRECTION" in your subject line. We prioritize these immediately.</p>
                <a href="{{ route('editorial-policy') }}" class="text-sm text-red-400 hover:text-red-300 font-semibold hover:underline">Read our Editorial Policy →</a>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="font-black text-gray-900 mb-3">Other Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-sm text-red-600 hover:underline font-medium">About WorldPulse24</a></li>
                    <li><a href="{{ route('editorial-policy') }}" class="text-sm text-red-600 hover:underline font-medium">Editorial Policy</a></li>
                    <li><a href="{{ route('privacy-policy') }}" class="text-sm text-red-600 hover:underline font-medium">Privacy Policy</a></li>
                    <li><a href="/admin" class="text-sm text-red-600 hover:underline font-medium">Admin Login</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
