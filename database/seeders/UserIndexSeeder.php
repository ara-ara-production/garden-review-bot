<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserIndexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test name',
            'email' => 'test@test.com',
            'telegram_username' => 'test',
        ]);

        User::factory(20)->create();
    }
}
