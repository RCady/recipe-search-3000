<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $recipe_id
 * @property int $sort_order
 * @property string $description
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property-read Recipe $recipe
 */
class RecipeStep extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    protected static function booted(): void
    {
        static::creating(function (RecipeStep $step) {
            if ($step->sort_order === null && $step->recipe_id) {
                $max = RecipeStep::query()->where('recipe_id', $step->recipe_id)->max('sort_order');
                $step->sort_order = $max !== null ? $max + 1 : 1;
            }
        });

        static::addGlobalScope('order', function (Builder $query) {
            $query->orderBy('sort_order');
        });
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
