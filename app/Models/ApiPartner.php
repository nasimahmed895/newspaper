<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ApiPartner extends Model
{
    protected static function booted(): void
    {
        static::creating(function (self $partner) {
            if (empty($partner->api_key)) {
                $partner->api_key = Str::random(64);
            }
        });
    }

    protected $fillable = [
        'name',
        'website_url',
        'api_key',
        'is_active',
        'notes',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active'    => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
