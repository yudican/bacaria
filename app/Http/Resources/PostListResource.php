<?php

namespace App\Http\Resources;

use App\Models\DataIklan;
use Illuminate\Http\Resources\Json\JsonResource;

class PostListResource extends JsonResource
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
            'uid_post' => $this->uid_post,
            'title' => $this->title,
            'type' => $this->type,
            'slug' => $this->slug,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments_count,
            'image_path' => $this->image_path,
            'time_ago' => $this->time_ago,
            'category_name' => $this->category_name,
            'author' => [
                'id' => $this->author_id,
                'name' => $this->author_name,
                'image' => $this->author_image,
                'verified' => false,
            ],
        ];
    }
}
