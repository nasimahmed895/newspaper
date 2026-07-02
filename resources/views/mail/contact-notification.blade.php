<!DOCTYPE html>
<html>
<body style="font-family:sans-serif;max-width:600px;margin:0 auto;padding:20px;color:#333">
<h2 style="color:#1a1a1a">New Contact Form Submission</h2>
<table style="width:100%;border-collapse:collapse">
    <tr><td style="padding:8px;font-weight:bold;width:120px">From:</td><td style="padding:8px">{{ $data['name'] }} &lt;{{ $data['email'] }}&gt;</td></tr>
    <tr style="background:#f9f9f9"><td style="padding:8px;font-weight:bold">Subject:</td><td style="padding:8px">{{ $data['subject'] }}</td></tr>
    @if(!empty($data['article_url']))
    <tr><td style="padding:8px;font-weight:bold">Article:</td><td style="padding:8px"><a href="{{ $data['article_url'] }}">{{ $data['article_url'] }}</a></td></tr>
    @endif
</table>
<div style="margin-top:20px;padding:16px;background:#f4f4f4;border-left:4px solid #e11d48;border-radius:4px">
    <p style="margin:0;white-space:pre-wrap">{{ $data['message'] }}</p>
</div>
<p style="margin-top:24px;font-size:12px;color:#999">Submitted from {{ config('app.name') }} — reply directly to this email to respond to {{ $data['name'] }}.</p>
</body>
</html>
