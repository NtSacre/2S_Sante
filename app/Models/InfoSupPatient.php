<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoSupPatient extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
