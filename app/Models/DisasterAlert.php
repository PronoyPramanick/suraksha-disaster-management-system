<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisasterAlert extends Model
{
    protected $fillable = [
        'title',
        'kind',
        'category',
        'severity',
        'location',
        'latitude',
        'longitude',
        'affected_area',
        'summary',
        'body',
        'safety_instructions',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }
}
