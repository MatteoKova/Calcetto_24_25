<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Player;
use App\Models\Game;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createUsersAndPlayers();

        $games = Game::factory(20)->create();

        foreach ($games as $game) {
            $players = Player::inRandomOrder()->take(10)->get();

            $team1 = $players->slice(0, 5);
            $team2 = $players->slice(5, 5);

            foreach ($team1 as $player) {
                $goals = rand(0, 5);

                $game->players()->attach($player->id, [
                    'team_number' => 1,
                    'goals' => $goals
                ]);

                $player->increment('totGames');
                $player->increment('totGoals', $goals);
            }

            foreach ($team2 as $player) {
                $goals = rand(0, 5);

                $game->players()->attach($player->id, [
                    'team_number' => 2,
                    'goals' => $goals
                ]);

                $player->increment('totGames');
                $player->increment('totGoals', $goals);
            }
        }
    }

    public function createUsersAndPlayers() {
        User::factory()->create([
            'name' => 'Ammini Stratore',
            'email' => 'a.stratore@example.com',
            'password' => Hash::make('stratore0!'),
            'role' => 'admin'
        ]);

        $path = database_path('data/players.json');
        $players = json_decode(file_get_contents($path), true);

        foreach ($players as $entry) {
            $user = User::factory()->create([
                'name' => $entry['user']['name'],
                'email' => $entry['user']['email'],
                'password' => Hash::make($entry['user']['password']),
            ]);

            Player::factory()->create(array_merge(
                $entry['player'],
                ['user_id' => $user->id]
            ));
        }
    }
}
