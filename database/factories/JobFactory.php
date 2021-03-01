<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakerJapanese = FakerFactory::create('ja_JP');
        return [
            'title' => $fakerJapanese->name,
            'content' => $fakerJapanese->text(50),
            'status' => rand(0, 1),
            'recruiting_start' => now(),
            'recruiting_end' => now(),
            'connect_areas' => [
                \App\Models\Province::all()->random()->_id,
                \App\Models\Province::all()->random()->_id,
                \App\Models\Province::all()->random()->_id
            ],
            'connect_skills' => [
                \App\Models\Skill::all()->random()->_id,
                \App\Models\Skill::all()->random()->_id,
                \App\Models\Skill::all()->random()->_id
            ],
            'user_id' => \App\Models\User::all()->random()->_id,
            'is_deleted' => false
        ];
    }
}
