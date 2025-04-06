<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        // Generate a unique slug based upon the recipe name
        static::creating(function (Recipe $recipe) {
            $baseSlug = Str::slug($recipe->name);
            $slug = $baseSlug;

            $count = 1;
            while (Recipe::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $recipe->slug = $slug;
        });
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class);
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }
}
