<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    protected ?string $apiKey;

    protected ?string $clientEmail;

    protected ?string $privateKey;

    protected string $scopes = 'https://www.googleapis.com/auth/indexing';

    public function __construct()
    {
        $this->apiKey = config('services.google.indexing_api_key');
        $this->clientEmail = config('services.google.indexing_client_email');
    }

    /**
     * Submit a URL to Google Indexing API for immediate indexing.
     */
    public function submitUrl(string $url, string $type = 'URL_UPDATED'): array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            Log::warning('Google Indexing: No access token available');
            return ['success' => false, 'error' => 'No access token'];
        }

        try {
            $response = Http::timeout(15)
                ->withToken($accessToken)
                ->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                    'url' => $url,
                    'type' => $type,
                ]);

            if ($response->successful()) {
                Log::info("Google Indexing: Successfully submitted {$url}", [
                    'response' => $response->json(),
                ]);
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::warning("Google Indexing API error for {$url}: {$response->body()}");
            return [
                'success' => false,
                'error' => $response->json('error.message', $response->body()),
            ];
        } catch (\Throwable $e) {
            Log::error("Google Indexing API request failed: {$e->getMessage()}");
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Submit multiple URLs to Google Indexing API.
     */
    public function submitBatch(array $urls, string $type = 'URL_UPDATED'): array
    {
        $results = [];

        foreach ($urls as $url) {
            $results[$url] = $this->submitUrl($url, $type);
        }

        return $results;
    }

    /**
     * Get a URL's indexing status from Google Indexing API.
     */
    public function getStatus(string $url): ?array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->withToken($accessToken)
                ->get('https://indexing.googleapis.com/v3/urlNotifications/metadata', [
                    'url' => $url,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Throwable $e) {
            Log::warning("Google Indexing status check failed: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Get an OAuth 2.0 access token using a JWT service account.
     */
    protected function getAccessToken(): ?string
    {
        if (empty($this->apiKey) || empty($this->clientEmail)) {
            return null;
        }

        // Use JWT to get access token
        $jwt = $this->createJwt();

        if (!$jwt) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->asForm()
                ->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            Log::warning('Google OAuth token request failed: ' . $response->body());
            return null;
        } catch (\Throwable $e) {
            Log::error("Google OAuth error: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Create a JWT for Google service account authentication.
     */
    protected function createJwt(): ?string
    {
        // Get private key from config or environment
        $privateKeyContent = env('GOOGLE_INDEXING_PRIVATE_KEY');

        if (empty($privateKeyContent)) {
            return null;
        }

        $now = time();
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $claim = [
            'iss' => $this->clientEmail,
            'scope' => $this->scopes,
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $privateKey = openssl_pkey_get_private($privateKeyContent);

        if (!$privateKey) {
            Log::error('Failed to load private key for JWT');
            return null;
        }

        $segments = [];
        $segments[] = $this->base64UrlEncode(json_encode($header));
        $segments[] = $this->base64UrlEncode(json_encode($claim));
        $signingInput = implode('.', $segments);

        $signature = '';
        if (!openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            Log::error('Failed to sign JWT');
            return null;
        }

        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
