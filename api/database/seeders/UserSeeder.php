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
        $user->name = config('auth.super_admin.name');
        $user->email = config('auth.super_admin.email');
        $user->password = bcrypt(config('auth.super_admin.password'));
        $user->role_id = config('auth.super_admin.role_id');
        $user->setActive();
        $user->save();
//        User::factory()->count(50)->create();

    }
}
