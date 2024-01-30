<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultation extends Model
{
    use HasFactory;

    public function patient(): BelongsTo
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
}
