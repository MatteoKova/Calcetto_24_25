<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class RealSeeder extends Seeder
{
    public function run()
    {
        $this->createUsersAndPlayers();

        $this->resetPlayerStats(Player::all());

        $this->seedGame(
            ["Mattia" => 5, "Matteo" => 3, "Riccardo" => 2, "Pashtim" => 0, "Gabriele" => 0],
            ["Paolo" => 4, "Luca" => 3, "Lorenzo" => 1, "Andrea" => 0, "Massimo" => 0],
            '2025-03-27', 'Carpenedolo', '21:00'
        );

        $this->seedGame(
            ["Giovanni" => 4, "Riccardo" => 4, "Gabriele" => 1, "Marco" => 1, "Matteo" => 0],
            ["Luca" => 3, "Andrea" => 2, "Lorenzo" => 2, "Giulio" => 2, "Federico" => 0],
            '2025-07-02', 'Carpenedolo', '21:00'
        );

        $this->seedGame(
            ["Giovanni" => 3, "Riccardo" => 4, "Gilberto" => 2, "Marco" => 2, "Matteo" => 0],
            ["Luca" => 3, "Andrea" => 2, "Lorenzo" => 3, "Giulio" => 3, "Federico" => 0],
            '2025-07-11', 'Lonato', '20:00'
        );
    }

    public function createUsersAndPlayers() {
        User::factory()->create([
            'name' => 'Amministratore',
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

    public function resetPlayerStats($players) {
        foreach ($players as $p) {
            Player::updateOrCreate(
                ['name' => $p['name']],
                [
                    'nickname' => $p['nickname'],
                    'favRole' => $p['favRole'],
                    'totGames' => 0,
                    'totGoals' => 0
                ]
            );
        }
    }

    public function seedGame($team1, $team2, $date, $location, $time) {
        $generalities = [
            'date' => Carbon::parse($date),
            'location' => $location,
            'time' => $time
        ];

        $this->createGame($team1, $team2, $generalities);
    }

    public function createGame($team1, $team2, $generalities) {
        $game = Game::updateOrCreate($generalities);

        // Cycle on each team
        foreach ([$team1, $team2] as $index => $team) {
            $teamNumber = $index + 1;

            // Cycle on each player of the team
            foreach ($team as $name => $goals) {
                $player = Player::where('name', $name)->first();
                
                GamePlayer::updateOrCreate([
                    'game_id' => $game->id,
                    'player_id' => $player->id,
                    'team_number' => $teamNumber,
                    'goals' => $goals
                ]);

                $player->increment('totGames');
                $player->increment('totGoals', $goals);
            }
        }
    }
}