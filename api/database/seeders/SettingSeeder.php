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

        $setting = Setting::where('key', 'meta')->first();
        if (!$setting) {
            Setting::create([
                'key' => 'meta',
                'value' => '[{"name":"Turkish","meta_description":"Компания Травел","meta_title":"Компания Травел","meta_keyword":"путешествия"},{"name":"Russian","meta_description":"путешествия, аренда машин в турции лучшая компания","meta_title":"Поиск трансферов в Турции","meta_keyword":"путешествия"},{"name":"English","meta_description":"travel company","meta_title":"Lux find Cars In Turkey","meta_keyword":"travel"}]',
                'serialized' => 1,
            ]);
        }
        $setting = Setting::where('key', 'site_info')->first();

        if (!$setting) {
            Setting::create([
                'key' => 'site_info',
                'value' => '{"name":"Lux Elit Travels","address":"Liman Mah. 7.Sok.Kösem Apt. №22/3","email":"villadom1@gmai.com","maps":"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12770.305088710937!2d30.6189308!3d36.8526229!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7c1e1929663c7e7a!2zVmlsbGEgRG9tIEVtbGFrIFJlYWwgRXN0YXRlINCd0LXQtNCy0LjQttC40LzQvtGB0YLRjCBMVEQu!5e0!3m2!1sru!2str!4v1649252403262!5m2!1sru!2str","country":"Turkey","city":"Antalya","instagram":"https://www.instagram.com/luxelittravel/","facebook":"https://www.facebook.com/LuxElitTravel/","date":"2012"}',
                'serialized' => 1,
            ]);
        }
        $setting = Setting::where('key', 'name')->first();

        if (!$setting) {
            Setting::create([
                'key' => 'name',
                'value' => "Lux Elit Travel",
                'serialized' => 0,
            ]);
        }
        $setting = Setting::where('key', 'name')->first();

        if (!$setting) {
            Setting::create([
                'key' => 'site_name',
                'value' => "Lux Elit Travel",
                'serialized' => 0,
            ]);
        }

    }
}
