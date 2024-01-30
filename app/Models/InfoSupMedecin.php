<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoSupMedecin extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id'
    ];

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
                /**
     * Get the consultation for the planning.
     */
    public function secteur_activite(): BelongsTo
    {
        return $this->belongsTo(SecteurActivite::class);
    }

                    /**
     * Get the consultation for the planning.
     */
    public function hopital(): BelongsTo
    {
        return $this->belongsTo(Hopital::class);
    }
}
