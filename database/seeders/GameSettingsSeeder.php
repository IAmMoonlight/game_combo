<?php

namespace Database\Seeders;

use App\Models\GameSettings;
use Illuminate\Database\Seeder;

class GameSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GameSettings::create([
            'type_answer' => 'solo'
        ]);
    }
}
