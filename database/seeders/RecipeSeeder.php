<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(25)
            ->has(
                Recipe::factory(10)
                    ->has(RecipeIngredient::factory(15), 'ingredients')
                    ->has(RecipeStep::factory(5), 'steps'),
                'recipes'
            )
            ->create();
    }
}
