<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RecipeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Recipe::query();

        if ($request->filled('email')) {
            $query->whereHas('author', function (Builder $query) use ($request) {
                $query->where('email', $request->get('email'));
            });
        }

        if ($request->filled('ingredient')) {
            $query->whereHas('ingredients', function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->get('ingredient') . '%');
            });
        }

        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function (Builder $query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhereHas('ingredients', function (Builder $query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('steps', function (Builder $query) use ($keyword) {
                        $query->where('description', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $query->orderBy('created_at', 'desc');

        return RecipeResource::collection($query->paginate(5));
    }

    public function show(Recipe $recipe): RecipeResource
    {
        $recipe->load(['ingredients', 'steps', 'author']);
        return new RecipeResource($recipe);
    }
}
