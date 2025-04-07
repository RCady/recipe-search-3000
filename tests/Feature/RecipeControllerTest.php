<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_recipes_when_no_search_params_are_used(): void
    {
        $this->setupGenericRecipes();

        $this->get('/api/recipes')
            ->assertOk()
            ->assertJsonFragment(['total' => 10]);
    }

    public function test_it_returns_recipes_by_author_email(): void
    {
        $this->setupGenericRecipes();

        $author = User::factory()->create(['email' => 'author@example.com']);
        $recipe = Recipe::factory()
            ->has(RecipeIngredient::factory()->count(10), 'ingredients')
            ->has(RecipeStep::factory()->count(4), 'steps')
            ->create(['author_id' => $author->id]);

        $this->get('/api/recipes?email=author@example.com')
            ->assertOk()
            ->assertJsonFragment(['total' => 1])
            ->assertJsonFragment([
                'slug' => $recipe->slug,
            ]);
    }

    public function test_it_returns_recipes_matching_keyword_in_name_description_ingredients_or_steps(): void
    {
        $this->setupGenericRecipes();
        $recipe1 = Recipe::factory()
            ->has(RecipeIngredient::factory()->count(10), 'ingredients')
            ->has(RecipeStep::factory()->count(4), 'steps')
            ->create([
                'name' => 'The Best Pumpernickel bread you\'ve ever had!'
            ]);

        $recipe2 = Recipe::factory()
            ->has(RecipeIngredient::factory()->count(10), 'ingredients')
            ->has(RecipeStep::factory()->count(4), 'steps')
            ->create([
                'description' => 'This is the story of my very pumpernickel life, do not jump to recipe, you don\'t want to miss this one.'
            ]);

        $recipe3 = Recipe::factory()
            ->has(
                RecipeIngredient::factory()
                    ->count(1)
                    ->state(['name' => 'pumpernickel flour']),
                'ingredients'
            )
            ->has(RecipeIngredient::factory()->count(9), 'ingredients')
            ->has(RecipeStep::factory()->count(4), 'steps')
            ->create();

        $recipe4 = Recipe::factory()
            ->has(RecipeIngredient::factory()->count(10), 'ingredients')
            ->has(RecipeStep::factory()->state(['description' => 'whisk the pumpernickel mix']), 'steps')
            ->has(RecipeStep::factory()->count(4), 'steps')
            ->create();

        $this->get('/api/recipes?keyword=pumpernickel')
            ->assertOk()
            ->assertJsonFragment(['total' => 4])
            ->assertJsonFragment(['slug' => $recipe1->slug])
            ->assertJsonFragment(['slug' => $recipe2->slug])
            ->assertJsonFragment(['slug' => $recipe3->slug])
            ->assertJsonFragment(['slug' => $recipe4->slug]);
    }

    public function test_it_returns_recipes_that_match_all_criteria(): void
    {
        $this->setupGenericRecipes();

        $author = User::factory()->create(['email' => 'author@example.com']);

        $recipe = Recipe::factory()
            ->has(RecipeIngredient::factory()->state(['name' => 'beet']), 'ingredients')
            ->has(RecipeIngredient::factory()->count(10), 'ingredients')
            ->has(RecipeStep::factory()->count(4), 'steps')
            ->create(['author_id' => $author->id, 'name' => 'The Best Beet Salad']);

        Recipe::factory()
            ->has(RecipeIngredient::factory()->count(10), 'ingredients')
            ->has(RecipeStep::factory()->count(4), 'steps')
            ->create(['author_id' => $author->id]);

        $this->get('/api/recipes?email=author@example.com&keyword=beet&ingredient=beet')
            ->assertOk()
            ->assertJsonFragment(['total' => 1])
            ->assertJsonFragment(['slug' => $recipe->slug]);
    }

    public function test_it_returns_pagination_details(): void
    {
        $this->setupGenericRecipes();

        $this->get('/api/recipes')
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'meta' => [ 'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total'],
                'links'
            ]);
    }

    public function test_it_returns_a_single_recipe(): void
    {
        $recipes = $this->setupGenericRecipes();

        $this->get('/api/recipes/' . $recipes->first()->slug)
            ->assertOk()
            ->assertJsonFragment(['slug' => $recipes->first()->slug])
            ->assertJsonStructure([
                'data' => ['author', 'created_at', 'updated_at', 'ingredients', 'name', 'slug', 'steps']
            ]);
    }

    /**
     * @param int $count
     * @return Collection<int, Recipe>
     */
    protected function setupGenericRecipes(int $count = 10): Collection
    {
        return Recipe::factory($count)
            ->has(RecipeIngredient::factory()->count(10), 'ingredients')
            ->has(RecipeStep::factory()->count(4), 'steps')
            ->create();
    }
}
