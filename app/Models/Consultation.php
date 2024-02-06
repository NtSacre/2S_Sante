<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultation extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

            /**
     * Get the consultation for the planning.
     */
    public function planning(): BelongsTo
    {
        return $this->belongsTo(Planning::class);
    }

    
    protected static function boot()
{
    parent::boot();

    static::creating(function ($consultation) {
        $consultation->rappel_at = now()->subHours(24);
    });
}
}
