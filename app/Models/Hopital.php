<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hopital extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    
    /**
     * Get the role for the medecn.
     */
    public function medecin(): HasMany
    {
        return $this->HasMany(User::class);
    }
        /**
     * Get the role for the medecn.
     */
    public function infoSupMedecin(): HasMany
    {
        return $this->HasMany(InfoSupMedecin::class);
    }
}
