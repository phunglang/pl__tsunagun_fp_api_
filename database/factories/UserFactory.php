<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Str;
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakerJapanese = FakerFactory::create('ja_JP');
        return [
            'avatar' => $this->faker->imageUrl(75, 75),
            'email' => 'test'.$this->faker->unique()->numberBetween(1, 50).'@gmail.com',
            'facebook_id' => Str::random(8),
            'line_id' => Str::random(8),
            'apple_id' => Str::random(8),
            'username' => $fakerJapanese->name,
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
            'role' => rand(0, 1),
            'sex' => rand(0, 1),
            'status' => rand(0, 2),
            'department' => rand(0, 2),
            'genre' => Str::random(10),
            'experience' => $fakerJapanese->text(50),
            'comment' => $fakerJapanese->text(20),
            'birthday' => now(),
            'websites' => [
                $this->faker->url,
                $this->faker->url
            ],
            'connect_areas' => [
                \App\Models\Province::all()->random()->_id,
                \App\Models\Province::all()->random()->_id,
                \App\Models\Province::all()->random()->_id
            ],
            'image' => $this->faker->imageUrl(75, 75),
            'ID_validate' => rand(0, 1),
            'is_deleted' => false,
            'note' => $fakerJapanese->text(20),
            'career' => $fakerJapanese->text(20),
        ];
    }
}
