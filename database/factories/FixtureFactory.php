<?php

namespace Database\Factories;

use App\Models\Fixture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FixtureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fixture::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team' => $this->faker->name,
            'stadium' => $this->faker->name,
            'goal_scored' => rand(0,10),
            'goal_conceded' => rand(0,10),
            'match_day' => now(),
        ];
    }
}
