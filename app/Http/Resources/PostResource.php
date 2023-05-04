<?php

namespace App\Http\Resources;

use App\Models\DataIklan;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ads = DataIklan::whereHas('jenisIklan', function ($query) {
            return $query->where('kode_jenis_iklan', 'ads-content');
        })->inRandomOrder()->first();
        return [
            'id' => $this->id,
            'uid_post' => $this->uid_post,
            'title' => $this->title,
            'type' => $this->type,
            'slug' => $this->slug,
            'caption' => $this->caption,
            'content' => $this->content,
            'status' => $this->status,
            'comment_status' => $this->comment_status,
            'publish_status' => $this->publish_status,
            'editor' => $this->editor,
            'created_at' => $this->created_at,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments_count,
            'image_path' => $this->image_path,
            'author_image' => $this->author_image,
            'author_name' => $this->author_name,
            'category_name' => $this->category_name,
            'tags' => $this->tags,
            'tag_lists' => $this->tag_lists,
            'time_ago' => $this->time_ago,
            'comments' => $this->comments,
            'ads' => $ads,
        ];
    }
}
