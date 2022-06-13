<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public array $localSeeders = [
        SettingSeeder::class,
        RoleSeeder::class,
        UserSeeder::class,
//        CompanySeeder::class,
//        CitySeeder::class,
//        CarSeeder::class,
//        TransferSeeder::class,
    ];
    public array $productionSeeders = [
        SettingSeeder::class,
        RoleSeeder::class,
        UserSeeder::class,
        CitySeeder::class,

    ];

    public function run()
    {
        // Отключаем логирование и проверку внешних ключей
        DB::disableQueryLog();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        switch (app()->environment()) {
            case 'local':
                foreach ($this->localSeeders as $seeder) {
                    //Очистка данных
                    $string = Str::remove(['database_seeders_', 'databases_', '_seeder', '\\'], str_replace('\\', '', Str::snake($seeder)));
                    $table = Str::plural($string);
                    DB::table($table)->truncate();
                    Storage::delete('public/images');
                    $this->call($seeder);
                }
                break;

            case 'production':
                foreach ($this->productionSeeders as $seeder) {
                    $string = Str::remove(['database_seeders_', 'databases_', '_seeder', '\\'], str_replace('\\', '', Str::snake($seeder)));
                    $table = Str::plural($string);
                    DB::table($table)->truncate();
                    Storage::delete('public/images');
                    $this->call($seeder);
                }
                break;
        }

        // Возвращаем все обратно
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        DB::enableQueryLog();
    }

}
