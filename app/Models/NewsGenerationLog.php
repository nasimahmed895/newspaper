<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsGenerationLog extends Model
{
    protected $fillable = [
        'topic',
        'status',
        'articles_count',
        'error_message',
        'response_data',
    ];

    protected function casts(): array
    {
        return [
            'articles_count' => 'integer',
            'response_data' => 'array',
        ];
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
