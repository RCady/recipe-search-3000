<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\RecipeStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeStepTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_orders_using_sort_order_by_default(): void
    {
        $recipe = Recipe::factory()
            ->has(RecipeStep::factory()->state(['sort_order' => 2]), 'steps')
            ->has(RecipeStep::factory()->state(['sort_order' => 1]), 'steps')
            ->has(RecipeStep::factory()->state(['sort_order' => 3]), 'steps')
            ->create();

        $this->assertEquals(1, $recipe->steps[0]->sort_order);
        $this->assertEquals(2, $recipe->steps[1]->sort_order);
        $this->assertEquals(3, $recipe->steps[2]->sort_order);
    }

    public function test_it_automatically_sets_sort_order(): void
    {
        $recipe = Recipe::factory()->create();
        $steps = RecipeStep::factory(3)->create(['recipe_id' => $recipe]);

        $this->assertEquals(1, $steps[0]->sort_order);
        $this->assertEquals(2, $steps[1]->sort_order);
        $this->assertEquals(3, $steps[2]->sort_order);
    }
}
