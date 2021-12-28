<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@admin',
                'password' => 'qweqweqwe',
                'type' => User::TYPE_USER_ADMIN
            ],
            [
                'name' => 'red user',
                'email' => 'red@red',
                'password' => 'qweqweqwe',
                'type' => User::TYPE_USER_GAMER
            ],
            [
                'name' => 'green user',
                'email' => 'green@green',
                'password' => 'qweqweqwe',
                'type' => User::TYPE_USER_GAMER
            ],
            [
                'name' => 'blue user',
                'email' => 'blue@blue',
                'password' => 'qweqweqwe',
                'type' => User::TYPE_USER_GAMER
            ],
            [
                'name' => 'black user',
                'email' => 'black@black',
                'password' => 'qweqweqwe',
                'type' => User::TYPE_USER_GAMER
            ],
        ];

        foreach ($users as $user){
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'type' => $user['type'],
            ]);
        }
    }
}
