<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteDetailResource extends JsonResource
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
            'id_user' => $this->id_user,
            'user' => $this->whenLoaded('User'),
            'note_title' => $this->note_title,
            'note_content' => $this->note_content,
            'created_at' => $this->created_at
        ];
    }
}