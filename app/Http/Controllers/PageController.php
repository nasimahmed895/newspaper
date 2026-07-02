<?php

namespace App\Http\Controllers;

use App\Mail\ContactNotification;
use App\Mail\NewsletterWelcome;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'email'       => 'required|email|max:255',
            'subject'     => 'required|string|max:100',
            'message'     => 'required|string|min:20|max:5000',
            'article_url' => 'nullable|url|max:500',
        ]);

        ContactSubmission::create(array_merge($data, ['ip' => $request->ip()]));

        $adminEmail = config('app.admin_email');
        if ($adminEmail) {
            Mail::to($adminEmail)->queue(new ContactNotification($data));
        }

        return back()->with('contact_success', 'Your message has been received. We will respond within 2 business days.');
    }

    public function newsletter(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255']);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $data['email']],
            ['ip' => $request->ip(), 'is_active' => true]
        );

        if ($subscriber->wasRecentlyCreated) {
            Mail::to($data['email'])->queue(new NewsletterWelcome());
        }

        return back()->with('newsletter_success', 'You are subscribed. Thank you for joining ' . config('app.name') . '.');
    }
}
