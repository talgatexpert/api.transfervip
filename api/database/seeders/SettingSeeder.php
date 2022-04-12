<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'key' => 'meta',
            'value' => json_encode([
                [
                    'name' => 'Turkish',
                    'meta_description' => 'Dünya çapında havaalanı, otel veya tren istasyonundan çevrimiçi taksi ve transferleri bulun ve rezervasyon yapın. Beat fix fiyatına, yüksek kaliteli hizmetlere ve farklı tipteki araçlara erişin.',
                    'meta_title' => env('APP_NAME') . '| dünya çapında konforlu ve karlı transferler. Havaalanından havaalanına konforlu arabalarla seyahat edin',
                    'meta_keyword' => 'transfer,Antalya, Türkiye',
                ],
                [
                    'name' => 'Russian',
                    'meta_description' => 'Найдите и закажите онлайн такси и трансфер из аэропорта, отеля или железнодорожного вокзала по всему миру. Получите доступ к лучшей фиксированной цене, высококачественным услугам и транспортным средствам различных типов.',
                    'meta_title' => env('APP_NAME') . '|  удобные и выгодные переводы по всему миру. Путешествуйте из аэропорта в аэропорт на комфортабельных автомобилях',
                    'meta_keyword' => 'трансфер,Анталья, Турция',
                ],
                [
                    'name' => 'English',
                    'meta_description' => 'Find and book online taxi & transfers from the airport, hotel or railway station worldwide. Get access to the best fix prices, high-quality services, and vehicles of different types.',
                    'meta_title' => env('APP_NAME') . '| comfortable and profitable transfers worldwide. Travel from the airport to the airport in comfortable cars',
                    'meta_keyword' => 'transfer,Antalya, Turkey',
                ],
            ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'serialized' => 1,
        ]);
    }
}
