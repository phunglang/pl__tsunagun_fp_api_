<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakerJapanese = FakerFactory::create('ja_JP');
        return [
            'own_id' => \App\Models\User::all()->random()->_id,
            'client_id' => \App\Models\User::all()->random()->_id,
            'status' => rand(0, 1),
            'content' => $fakerJapanese->text(50),
            'file' => []
        ];
    }
}
