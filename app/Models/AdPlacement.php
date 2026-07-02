<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPlacement extends Model
{
    protected $fillable = [
        'name',
        'location',
        'code',
        'image_url',
        'link_url',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function getByLocation(string $location): ?self
    {
        return static::where('location', $location)
            ->where('is_active', true)
            ->first();
    }
}
