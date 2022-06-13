<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'super_admin')->first();
        if (!$role) {
            Role::create([
                'description' => 'Sistem Yönetici',
                'name' => 'super_admin',
                'permissions' => json_encode(User::ABILITIES['SUPER_ADMIN']),
            ]);
        }
        $role = Role::where('name', 'admin')->first();
        if (!$role) {
            Role::create([
                'description' => 'Yönetici',
                'name' => 'admin',
                'permissions' => json_encode(User::ABILITIES['ADMIN_ABILITIES']),
            ]);
        }
        $role = Role::where('name', 'client')->first();
        if (!$role) {
            Role::create([
                'description' => 'Müşteri',
                'name' => 'client',
                'permissions' => json_encode(User::ABILITIES['CLIENT_ABILITIES']),
            ]);
        }
        $role = Role::where('name', 'company')->first();
        if (!$role) {

            Role::create([
                'description' => 'Transfer Şirket',
                'name' => 'company',
                'permissions' => json_encode(User::ABILITIES['COMPANY_ABILITIES']),
            ]);
        }
        $role = Role::where('name', 'travel')->first();
        if (!$role) {
            Role::create([
                'description' => 'Seyahat Şirketi',
                'name' => 'travel',
                'permissions' => json_encode(User::ABILITIES['TRAVEL_ABILITIES']),
            ]);
        }
    }
}
