<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'titre'=> $this->titre,
            'description'=> $this->description,
            'image'=> $this->image,
            'medecin'=> $this->medecin->nom,

            'created_at'=> $this->created_at
        ];
    }
}
