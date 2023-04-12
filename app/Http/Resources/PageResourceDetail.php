<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResourceDetail extends JsonResource
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
            'title' => $this->judul,
            'slug' => $this->slug,
            'content' => $this->isi,
            'banner_url' => $this->banner_url,
            'category_name' => $this->category_name,
            'author' => [
                'name' => 'Admin',
                'image' => 'https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff'
            ],
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
