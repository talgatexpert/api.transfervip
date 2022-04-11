<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = 'https://gist.githubusercontent.com/ozdemirburak/4821a26db048cc0972c1beee48a408de/raw/4754e5f9d09dade2e6c461d7e960e13ef38eaa88/cities_of_turkey.json';
        $result = Http::get($url);

        foreach ($result->object() as $item) {
            $city = new City();
            $city->name = $item->name;
            $translations = [
                'turkish' => $item->name,
                'russian' => $item->name,
                'english' => $item->name,
            ];
            $city->setTranslations('translations', $translations);
            $city->save();
        }
    }
}
