<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedecinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('infoSupMedecin');
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'image' => $this->infoSupMedecin->image,
            'genre' => $this->genre,
            'ville' => $this->ville->nom,
            'hopital' => $this->infoSupMedecin->hopital->nom,
            'role' => $this->role->nom,

            'secteur_activite' => $this->infoSupMedecin->secteur_activite->nom,
        ];
    }
}
