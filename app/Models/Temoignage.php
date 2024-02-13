<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Temoignage extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    
    /**
     * Get the role for the medecn.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
