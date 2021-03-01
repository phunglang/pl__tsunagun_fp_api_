<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

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
            'content' => $this->faker->text,
            'own_id' => \App\Models\User::all()->random()->_id,
            $type.'_id' => $test::all()->random()->_id,
            'reason'  => $this->faker->text
        ];
    }
}
