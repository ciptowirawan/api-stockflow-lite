<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token'  => $this->access_token,
            'expires_in'    => $this->expires_in,
            'token_type'    => $this->token_type,
            'user'      => [
                'id'    => $this->user->id,
                'nama'  => $this->user->name,
                'email' => $this->user->email,
            ],
        ];
    }
}
