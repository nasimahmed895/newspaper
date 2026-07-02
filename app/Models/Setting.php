<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
        'type',
        'options',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function setValue(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
    }

    public static function getGroup(string $group): array
    {
        return Cache::remember("settings.{$group}", 3600, function () use ($group) {
            return static::where('group', $group)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::flush();
        });

        static::deleted(function () {
            Cache::flush();
        });
    }
}
