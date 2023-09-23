<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'roleId' => $this->role_id,
            'createdAt' => $this->created_at->format('F j, Y, g:i A'),
            'updatedAt' => $this->updated_at->format('F j, Y, g:i A'),
        ];
    }
}
