<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RescueRequestActivity extends Model
{
    protected $fillable = ['rescue_request_id', 'user_id', 'action', 'from_status', 'to_status', 'note'];

    public function rescueRequest(): BelongsTo
    {
        return $this->belongsTo(RescueRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
