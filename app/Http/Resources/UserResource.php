<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'google_id' => $this->google_id,
            'facebook_id' => $this->facebook_id,
            'role' => $this->role,
            'profile_image' => $this->profile_photo_path ? asset('storage/' . $this->profile_photo_path) : $this->profile_photo_url,
        ];
    }
}
