<?php

namespace Database\Factories;

use App\Models\NGword;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class NGwordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NGword::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakerJapanese = FakerFactory::create('ja_JP');
        return [
            'name' => $fakerJapanese->text(10)
        ];
    }
};
