<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRecipeCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fails_if_author_does_not_exist(): void
    {
        $this->artisan('recipes:create "My Recipe" test@test.com')
            ->expectsOutput('Author not found')
            ->assertExitCode(1);
    }

    public function test_it_fails_if_steps_or_ingredients_are_not_present(): void
    {
        $author = User::factory()->create();
        $this->artisan('recipes:create "My Recipe" ' . $author->email . ' --ingredients=0.5,c,flour')
            ->expectsOutput('Ingredients and steps are required using the --ingredients and --steps options')
            ->assertExitCode(1);

        $author = User::factory()->create();
        $this->artisan('recipes:create "My Recipe" ' . $author->email . ' --steps="Whisk together"')
            ->expectsOutput('Ingredients and steps are required using the --ingredients and --steps options')
            ->assertExitCode(1);
    }

    public function test_it_fails_if_ingredients_do_not_contain_all_parts(): void
    {
        $author = User::factory()->create();
        $this->artisan('recipes:create "My Recipe" ' . $author->email . ' --steps="Whisk together" --ingredients=c,flour')
            ->expectsOutput('Ingredients are improperly formatted')
            ->assertExitCode(1);
    }

    public function test_it_creates_a_new_recipe(): void
    {
        $author = User::factory()->create();
        $this->artisan('recipes:create "My Recipe" ' . $author->email . ' --description="My Description" --steps="Whisk together" --ingredients=1,c,flour')
            ->expectsOutput('Recipe Created Successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('recipes', [
            'name' => 'My Recipe',
            'description' => 'My Description',
            'author_id' => $author->id
        ]);

        $this->assertDatabaseHas('recipe_ingredients', [
            'name' => 'flour',
            'unit' => 'c',
            'amount' => '1'
        ]);

        $this->assertDatabaseHas('recipe_steps', [
            'sort_order' => 1,
            'description' => 'Whisk together'
        ]);
    }
}
