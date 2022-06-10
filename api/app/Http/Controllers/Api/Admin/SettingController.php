<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SettingResource;
use App\Models\Setting;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use  \Illuminate\Contracts\Foundation\Application;
use  \Illuminate\Contracts\Routing\ResponseFactory;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|ResponseFactory|Response | SettingResource
     */
    public function index(Request $request)
    {
        $r = $request->get('key') ?? 'meta';
        $setting = Setting::where('key', $r)->firstOrFail();

        if ($setting) {
            return new SettingResource($setting);
        }
        return response(['message' => "Setting doesn't exists"], 404);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Setting
     */


    private function updateSetting(Setting $setting, Request $request, string|null $image = null)
    {

        $setting->key = $request->get('key');
        if ($request->exists('serialized')) {
            $setting->value = json_encode($request->value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $setting->serialized = 1;
        } else {
            $setting->value = $request->get('value');
        }
        if ($image) {
            $setting->value = $image;
        }

        $setting->save();
        return $setting;
    }

    public function store(Request $request)
    {
        $setting = Setting::where('key', $request->get('key'))->first();
        if (!$setting) {
            $setting = new Setting();
        }
        $setting = $this->updateSetting($setting, $request, $this->saveFile($request));

        return new SettingResource($setting);

    }

    /**
     * Display the specified resource.
     *
     * @return SettingResource
     */
    public function show(Request $request, string $setting)
    {
        $setting = Setting::where('key', $setting)->first();

        if ($setting) {
            return new SettingResource($setting);
        }
        return response(['message' => "Setting doesn't exists"], 404);
    }

    private function saveFile(Request $request): null|string
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = Str::random(18) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $fileName);
            return  Storage::disk('public')->url('public/images/' . $fileName);
        }
        return null;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request): Response|SettingResource
    {
        $setting = Setting::where('key', $request->get('key'))->first();
        if (!$setting) {
            $setting = new Setting();
        }
        $setting = $this->updateSetting($setting, $request, $this->saveFile($request));

        return new SettingResource($setting);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
