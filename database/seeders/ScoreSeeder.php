<?php

namespace Database\Seeders;

use App\Models\ScoreUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach($users as $user){
            ScoreUser::create([
                'user_id' => $user->id
            ]);
        }
    }
}
