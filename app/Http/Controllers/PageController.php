<?php

namespace App\Http\Controllers;

use App\Mail\ContactNotification;
use App\Mail\NewsletterWelcome;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class PageController extends Controller
{
    public function about()
    {
        $defaultSections = [
            ['World News', '#EF4444'],
            ['Politics', '#DC2626'],
            ['Business', '#10B981'],
            ['Technology', '#3B82F6'],
            ['Science', '#8B5CF6'],
            ['Health', '#F59E0B'],
            ['Sports', '#06B6D4'],
            ['Entertainment', '#EC4899'],
        ];

        $coverages = json_decode(
            Setting::getValue('about_coverages', json_encode($defaultSections)),
            true
        );

        return view('pages.about', compact('coverages'));
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
            $unsubscribeUrl = URL::signedRoute('newsletter.unsubscribe', ['email' => $data['email']]);
            Mail::to($data['email'])->queue(new NewsletterWelcome($unsubscribeUrl));
        }

        return back()->with('newsletter_success', 'You are subscribed. Thank you for joining ' . config('app.name') . '.');
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');

        if ($email) {
            NewsletterSubscriber::where('email', $email)->update(['is_active' => false]);
        }

        return view('pages.unsubscribed');
    }
}
