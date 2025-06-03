<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'macros' => $this->macros,
            'ingredients' => $this->ingredients,
            'image_path' => $this->image_path,
            'created_at' => $this->created_at,
        ];
    }
}
