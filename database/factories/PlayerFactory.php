<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\DataLayer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    protected $model = Player::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dl = new DataLayer();

        return [
            'name' => $this->faker->firstName,
            'nickname' => $this->faker->firstName,
            'favRole' => $this->faker->randomElement($dl->listRoles()),
            'totGames' => 0,
            'totGoals' => 0,
            'avatar' => 'avatars/defaultAvatar.png'
        ];
    }
}