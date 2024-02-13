<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HopitalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
<<<<<<< HEAD

=======
>>>>>>> 720bc2b6e02b26e4ee6a8224f3bf041d3208468c
            'nom'=> $this->nom
        ];
    }
}
