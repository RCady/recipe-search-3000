<?php

namespace Tests\Feature;

use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_slug_based_on_the_name(): void
    {
        $recipe = Recipe::factory()->create([
            'name' => 'My Very First Recipe'
        ]);

        $this->assertEquals('my-very-first-recipe', $recipe->slug);
    }

    public function test_it_handles_duplicate_slugs(): void
    {
        $recipes = Recipe::factory(3)
            ->create([
                'name' => 'The Best Chicken Biryani You\'ll Ever Eat!'
            ]);

        $this->assertEquals('the-best-chicken-biryani-youll-ever-eat', $recipes[0]->slug);
        $this->assertEquals('the-best-chicken-biryani-youll-ever-eat-1', $recipes[1]->slug);
        $this->assertEquals('the-best-chicken-biryani-youll-ever-eat-2', $recipes[2]->slug);
    }
}
