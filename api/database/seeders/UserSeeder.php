<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = env('SUPER_ADMIN_NAME', 'Fatih Celik');
        $user->email = env('SUPER_ADMIN_EMAIL', 'admin@vitotransfer.ru');
        $user->password = bcrypt(env('SUPER_ADMIN_PASSWORD', '123'));
        $user->role_id = User::ROLES['super_admin'];
        $user->save();
        User::factory()->count(50)->create();

    }
}
