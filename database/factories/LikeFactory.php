<?php

namespace Database\Factories;

use App\Models\Like;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['user', 'post', 'job']);
        $model = ucfirst(strtolower($type));
        $test = "\App\Models\\{$model}";
        return [
            'own_id' => \App\Models\User::all()->random()->_id,
            $type.'_id' => $test::all()->random()->_id,
        ];
    }
};
