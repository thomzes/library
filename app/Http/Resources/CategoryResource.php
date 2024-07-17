<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Hide the 'id' attribute
        return [
            'name' => $this->name,
            'id' => $this->id,
            'books' => BookResource::collection($this->whenLoaded('books')),
        ];
    }
}
