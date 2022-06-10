<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SettingResource;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MetaController extends Controller
{
    public function index()
    {
        $data = [];
        $settings = Setting::meta()->first();
        $name = Setting::name()->first();
        if ($settings){
            $data['meta'] =  $settings->value;
        }
        if ($name){
            $data['name'] = $name->value;
        }

        return response(['data' => $data]);
    }
    public function settings($name): SettingResource
    {
        $data = [];
        $data[$name] = null;
        $settings = Setting::where('key', $name)->first();

        return new SettingResource($settings);
    }
}
