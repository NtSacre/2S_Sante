<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanningResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'jour'=> $this->jour,
            'heure_debut'=> $this->heure_debut,
            'heure_fin'=> $this->heure_fin,
            'medecin'=> new MedecinResource($this->user),
    
            'created_at'=> $this->created_at
        ];
    }
}
