<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'score' => $this->score,
            'processed_at' => $this->processed_at,
            'created_at' => $this->created_at->toDateTimeString(), // Formata a data
            'updated_at' => $this->updated_at->toDateTimeString(), // Formata a data
        ];
    }
}
