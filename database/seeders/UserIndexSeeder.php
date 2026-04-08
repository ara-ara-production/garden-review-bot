<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use App\Models\UserMessengerAccount;
use Illuminate\Database\Seeder;

class UserIndexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test name',
            'email' => 'test@test.com',
            'role' => UserRoleEnum::Founder,
        ]);

        UserMessengerAccount::create([
            'user_id' => $user->id,
            'driver' => 'telegram',
            'username' => 'test',
        ]);

        User::factory(20)->create();
    }
}
