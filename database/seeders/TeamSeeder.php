<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * @var string[]
     */
    private $teamNames = ['Chelsea', 'Arsenal', 'Manchester City', 'Liverpool'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->teamNames as $teamName) {
            Team::factory()->times(1)->create(['name' => $teamName]);
        }
    }
}
