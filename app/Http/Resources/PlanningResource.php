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
        $this->load('medecin');
        return [
            'id'=>$this->id,
            'jour'=> $this->jour,
            'creneaux'=> json_decode($this->creneaux, true),

            'status'=> $this->status,
            'medecin'=> new MedecinResource($this->medecin),
    
            'created_at'=> $this->created_at
        ];
    }
}
