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
            'name' => $this->name,
            'phone' => $this->phone,
            'login' => $this->login,
            'id_1c' => $this->id_1c,
            'device_token' => $this->device_token,
            'status' => $this->status,
            'lat' => $this->lat,
            'store' => $this->store,
            'storage' => $this->storage,
            'organization' => $this->organization,
            'bank' => $this->bank,
        ];
    }
}
