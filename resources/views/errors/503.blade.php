<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Down for Maintenance — {{ config('app.name', 'WorldPulse24') }}</title>
    <style>
        body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: #09090b; color: #fafafa; text-align: center; }
        .wrap { max-width: 420px; padding: 2rem; }
        h1 { font-size: 2rem; font-weight: 800; color: #dc2626; margin: 0 0 .5rem; }
        p { color: #a1a1aa; line-height: 1.6; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>{{ config('app.name', 'WorldPulse24') }}</h1>
    <p>We're performing scheduled maintenance and will be back shortly. Thank you for your patience.</p>
</div>
</body>
</html>
