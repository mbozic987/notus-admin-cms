<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 1, 1000),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $categories = Category::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $product->categories()->syncWithoutDetaching($categories);

            $commentsCount = rand(5, 20);
            for ($i = 0; $i < $commentsCount; $i++) {
                $user = null;
                if (rand(0, 1) == 1) {
                    $user = User::role('user')->inRandomOrder()->first();
                }
                Comment::factory()->create([
                    'product_id' => $product->id,
                    'user_id' => $user ? $user->id : null,
                ]);
            }
        });
    }
}
