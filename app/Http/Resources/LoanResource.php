<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'borrower' => $this->borrower,
            'borrowed_at' => $this->borrowed_at,
            'returned_at' => $this->returned_at,
            'book' => [
                'id' => $this->book->id,
                'title' => $this->book->title,
                'author' => $this->book->author,
            ],
        ];
    }
}
