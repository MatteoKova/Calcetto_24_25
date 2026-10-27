<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\dataLayer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dl = new DataLayer();
        
        return [
            'date' => $this->faker->dateTimeBetween('2025-01-01', now())->format('Y-m-d'),
            'time'=>$this->faker->time(),
            'location'=>$this->faker->randomElement($dl->listLocations())
        ];
    }
}