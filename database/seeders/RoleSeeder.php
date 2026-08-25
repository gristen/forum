<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{

    private array $roles = [
        'admin',
        'moderator',
        'user',
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(
            array_map(fn($name)=>['name'=>$name],$this->roles)
        );
    }
}
