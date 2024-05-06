<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'grade' => rand(null ,5)
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Comment $comment) {
            $user = null;
            if (rand(0, 1) == 1) {
                $user = User::role('user')->inRandomOrder()->first();
            }
            $comment->user_id = $user ? $user->id : null;
            $comment->save();
        });
    }
}
