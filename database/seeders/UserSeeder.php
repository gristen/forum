<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => "admin",
            'email' => "admin@mail.ru",
            "about" => "admin forum",
            'email_verified_at' => now(),
            'avatar' => 'default.svg',
            "password" => \Hash::make('1234'),
            'remember_token' => Str::random(10),
            'role_id'=> "1",
        ]);
        User::factory(20)->create();
    }
}
