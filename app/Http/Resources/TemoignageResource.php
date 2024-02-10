<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemoignageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('patient');
        return [
            'id' => $this->id,
            'temoignage' => $this->temoignage,
            'created_at' => $this->created_at,
            'patient' => $this->patient->nom
        ];
    }
}
