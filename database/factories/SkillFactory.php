<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class SkillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Skill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakerJapanese = FakerFactory::create('ja_JP');
        return [
            'name' => $fakerJapanese->name,
            'status' => rand(0, 1),
            'is_deleted' => false
        ];
    }
}
