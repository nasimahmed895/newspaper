<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            $settings = Setting::all(['key', 'value']);

            foreach ($settings as $setting) {
                $key = $setting->key;

                $configPath = match (true) {
                    str_starts_with($key, 'site.')     => str_replace('site.', 'app.', $key),
                    str_starts_with($key, 'seo.')      => str_replace('seo.', 'app.', $key),
                    str_starts_with($key, 'social.')   => $key,
                    default                            => $key,
                };

                if (config()->has($configPath) || str_contains($configPath, '.')) {
                    $value = match (true) {
                        $setting->value === 'true'  => true,
                        $setting->value === 'false' => false,
                        default                     => $setting->value,
                    };

                    config([$configPath => $value]);
                }
            }
        } catch (\Exception $e) {
            // Table may not exist during migrations
        }
    }
}
