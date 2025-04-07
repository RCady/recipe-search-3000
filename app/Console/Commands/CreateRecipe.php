<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateRecipe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipes:create {name} {author} {--description=} {--ingredients=*} {--steps=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new recipe';

    /**
     * Execute the console command.
     *
     * Usage
     *
     * Ingredient format: amount,unit,ingredient
     * artisan recipes:create "My Favorite Recipe" author@email.com \
     *  --description "This is my description" \
     *  --ingredients 1,cup,flour \
     *  --ingredients 0.5,cup,sugar \
     *  --steps "Add flour" \
     *  --steps "Add sugar" \
     *  --steps "Whisk together"
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $authorEmail = $this->argument('author');
        $description = $this->option('description');
        $ingredients = $this->option('ingredients');
        $steps = $this->option('steps');

        /** @var ?User $author */
        $author = User::query()->where('email', $authorEmail)->first();

        if (!$author) {
            $this->error('Author not found');
            return 1;
        }

        if (empty($ingredients) || empty($steps)) {
            $this->error('Ingredients and steps are required using the --ingredients and --steps options');
            return 1;
        }

        $processedIngredients = [];
        foreach ($ingredients as $ingredient) {
            $parts = explode(',', $ingredient);

            if (count($parts) < 3) {
                $this->error('Ingredients are improperly formatted');
                return 1;
            }

            $processedIngredients[] = [
                'amount' => $parts[0],
                'unit' => $parts[1],
                'name' => $parts[2]
            ];
        }

        DB::beginTransaction();
        try {
            /** @var Recipe $recipe */
            $recipe = Recipe::query()->create([
                'name' => $name,
                'description' => $description,
                'author_id' => $author->id,
            ]);

            $recipe->ingredients()->createMany($processedIngredients);
            $recipe->steps()->createMany(array_map(fn ($step) => ['description' => $step], $steps));

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            return 1;
        }

        $this->info('Recipe Created Successfully!');
        $this->info($recipe->refresh()->load('steps', 'ingredients')->toJson(JSON_PRETTY_PRINT));

        return 0;
    }
}
