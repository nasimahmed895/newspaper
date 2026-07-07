<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    private const BOT_PATTERNS = [
        'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'googlebot',
        'bingbot', 'yandex', 'baidu', 'facebookexternalhit', 'twitterbot',
        'linkedinbot', 'whatsapp', 'curl', 'wget', 'python', 'go-http',
        'java/', 'ruby', 'php/', 'libwww', 'httpunit', 'nutch', 'daum',
        'msnbot', 'ia_archiver', 'ahrefs', 'semrush', 'dotbot', 'petalbot',
    ];

    private const SOCIAL_DOMAINS = [
        'facebook.com', 'fb.com', 'twitter.com', 't.co', 'x.com',
        'instagram.com', 'linkedin.com', 'pinterest.com', 'tiktok.com',
        'reddit.com', 'youtube.com', 'whatsapp.com', 'telegram.org',
        'snapchat.com', 'tumblr.com',
    ];

    private const SEARCH_DOMAINS = [
        'google.', 'bing.com', 'yahoo.com', 'duckduckgo.com', 'baidu.com',
        'yandex.', 'ask.com', 'ecosia.org', 'brave.com', 'qwant.com',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            $this->record($request);
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if ($response->getStatusCode() !== 200) return false;
        if ($request->is('admin*', 'api/*', '_debugbar*', 'livewire*')) return false;
        if ($request->ajax() || $request->wantsJson()) return false;

        $ua = strtolower($request->userAgent() ?? '');
        foreach (self::BOT_PATTERNS as $pattern) {
            if (str_contains($ua, $pattern)) return false;
        }

        return true;
    }

    private function record(Request $request): void
    {
        try {
            PageView::create([
                'article_id'      => $this->resolveArticleId($request),
                'session_id'      => hash('sha256', session()->getId()),
                'ip_hash'         => hash('sha256', $request->ip() . date('Y-m-d') . config('app.key')),
                'page_type'       => $this->resolvePageType($request),
                'device_type'     => $this->detectDevice($request->userAgent() ?? ''),
                'browser'         => $this->detectBrowser($request->userAgent() ?? ''),
                'os'              => $this->detectOs($request->userAgent() ?? ''),
                'referrer_source' => $this->resolveReferrerSource($request),
                'created_at'      => now(),
            ]);
        } catch (\Throwable) {
            // Never break page load due to analytics failure
        }
    }

    private function resolvePageType(Request $request): string
    {
        return match ($request->route()?->getName()) {
            'home'            => 'home',
            'articles.show'   => 'article',
            'categories.show' => 'category',
            'search'          => 'search',
            default           => 'other',
        };
    }

    private function resolveArticleId(Request $request): ?int
    {
        if ($request->route()?->getName() !== 'articles.show') {
            return null;
        }

        $slug = $request->route('slug');
        if (!$slug) return null;

        return \App\Models\Article::where('slug', $slug)->value('id');
    }

    private function resolveReferrerSource(Request $request): string
    {
        $referrer = strtolower($request->header('referer', ''));

        if (empty($referrer)) return 'direct';

        // Same-site referrals don't count as external
        $appHost = strtolower(parse_url(config('app.url'), PHP_URL_HOST) ?? '');
        if ($appHost && str_contains($referrer, $appHost)) return 'direct';

        foreach (self::SEARCH_DOMAINS as $domain) {
            if (str_contains($referrer, $domain)) return 'organic';
        }

        foreach (self::SOCIAL_DOMAINS as $domain) {
            if (str_contains($referrer, $domain)) return 'social';
        }

        return 'referral';
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'ipad') || (str_contains($ua, 'android') && !str_contains($ua, 'mobile')) || str_contains($ua, 'tablet')) {
            return 'tablet';
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'iphone') || str_contains($ua, 'ipod') || str_contains($ua, 'android')) {
            return 'mobile';
        }
        return 'desktop';
    }

    private function detectBrowser(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'edg/') || str_contains($ua, 'edge/'))   return 'Edge';
        if (str_contains($ua, 'opr/') || str_contains($ua, 'opera'))   return 'Opera';
        if (str_contains($ua, 'brave'))                                  return 'Brave';
        if (str_contains($ua, 'chrome') && !str_contains($ua, 'chromium')) return 'Chrome';
        if (str_contains($ua, 'chromium'))                               return 'Chromium';
        if (str_contains($ua, 'firefox') || str_contains($ua, 'fxios')) return 'Firefox';
        if (str_contains($ua, 'safari') && !str_contains($ua, 'chrome')) return 'Safari';
        if (str_contains($ua, 'msie') || str_contains($ua, 'trident'))  return 'IE';
        return 'Other';
    }

    private function detectOs(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'windows nt')) return 'Windows';
        if (str_contains($ua, 'iphone') || str_contains($ua, 'ipad') || str_contains($ua, 'ipod')) return 'iOS';
        if (str_contains($ua, 'mac os x') || str_contains($ua, 'macos')) return 'macOS';
        if (str_contains($ua, 'android')) return 'Android';
        if (str_contains($ua, 'linux')) return 'Linux';
        if (str_contains($ua, 'chromeos') || str_contains($ua, 'cros')) return 'ChromeOS';
        return 'Other';
    }
}
