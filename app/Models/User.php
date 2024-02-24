<?php

namespace App\Models;

use App\Models\Role;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable  implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
        protected $guarded = [
            'id'
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

             /**
     * Get the role for the medecn.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
      /**
     * Get the ville for the medecn.
     */
    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }

      /**
     * Get the hopital for the medecn.
     */
    public function hopital(): BelongsTo
    {
        return $this->belongsTo(Hopital::class);
    }

          /**
     * Get the hopital for the medecn.
     */
    public function secteuractivite(): BelongsTo
    {
        return $this->belongsTo(SecteurActivite::class);
    }

      /**
     * Get the Article for the medecin.
     */
    public function article(): HasMany
    {
        return $this->HasMany(Article::class);
    }

          /**
     * Get the planning for the medecin.
     */
    public function planning(): HasMany
    {
        return $this->HasMany(Planning::class);
    }

              /**
     * Get the temoignage for the medecin.
     */
    public function Temoignage(): HasMany
    {
        return $this->HasMany(Temoignage::class);
    }
      /**
     * Get the consultation for the medecin.
     */
    public function consultations()
{
    return $this->hasManyThrough(Consultation::class, Planning::class,'user_id', 'planning_id', 'id', 'id');
}
      /**
     * Get the consultation for the medecin.
     */
    public function infoSupMedecin()
    {
        return $this->hasOne(InfoSupMedecin::class);
    }
      /**
     * Get the consultation for the medecin.
     */
    public function infoSupPatient(): BelongsTo
    {
        return $this->belongsTo(InfoSupPatient::class);
    }
    public function roleIsValid()
{
    // Vérifier si l'utilisateur a un rôle qui existe dans la table des rôles
    return Role::where('nom', $this->role)->exists();
}

}
