<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property ?int $author_id
 * @property string $name
 * @property ?string $description
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 *
 * @property-read User $author
 * @property-read Collection<RecipeStep> $steps
 * @property-read Collection<RecipeIngredient> $ingredients
 */
class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'author_id'];

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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
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
