<?php

namespace App\Http\Resources;

use App\Models\RecipeIngredient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'author' => $this->author?->email ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'steps' => RecipeStepResource::collection($this->whenLoaded('steps')),
            'ingredients' => RecipeIngredientResource::collection($this->whenLoaded('ingredients')),
        ];
    }
}
