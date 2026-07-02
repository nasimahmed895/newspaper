<!DOCTYPE html>
<html>
<body style="font-family:sans-serif;max-width:600px;margin:0 auto;padding:20px;color:#333">
<h2 style="color:#e11d48">Welcome to {{ config('app.name') }}!</h2>
<p>You're now subscribed to our newsletter. You'll receive updates on the biggest stories as they break.</p>
<p>We cover: Technology, Politics, Business, Health, Sports, Entertainment, and World News — fresh articles published every 2 hours.</p>
<p>Visit us anytime at <a href="{{ config('app.url') }}" style="color:#e11d48">{{ config('app.url') }}</a></p>
<hr style="border:none;border-top:1px solid #eee;margin:24px 0">
<p style="font-size:12px;color:#999">
    You received this because you subscribed at {{ config('app.name') }}.
    @if(!empty($unsubscribeUrl))
        <a href="{{ $unsubscribeUrl }}" style="color:#999">Unsubscribe</a>
    @endif
</p>
</body>
</html>
