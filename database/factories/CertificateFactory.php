<?php

namespace Database\Factories;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Certificate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'images' => [
                $this->faker->imageUrl(500, 500, 'cats'),
                $this->faker->imageUrl(500, 500, 'cats')
            ],
            'status' => rand(-1, 1),
            'user_id' => \App\Models\User::all()->random()->_id,
            'skill_id' => \App\Models\Skill::all()->random()->_id
        ];
    }
}
