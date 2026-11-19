<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class RescueRequest extends Model
{
    protected $fillable = [
        'user_id', 'contact_name', 'contact_phone', 'location', 'description',
        'people_count', 'urgency', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(RescueRequestActivity::class);
    }
}
