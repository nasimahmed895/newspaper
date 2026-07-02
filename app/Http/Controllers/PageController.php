<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function editorialPolicy()
    {
        return view('pages.editorial-policy');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|min:20|max:5000',
            'article_url' => 'nullable|url|max:500',
        ]);

        \Illuminate\Support\Facades\Log::info('Contact form submission', [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'article_url' => $request->article_url,
            'message' => substr($request->message, 0, 200),
        ]);

        return back()->with('contact_success', 'Your message has been received. We will respond within 2 business days.');
    }

    public function newsletter(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        return back()->with('newsletter_success', 'You are subscribed. Thank you for joining WorldPulse24.');
    }
}
