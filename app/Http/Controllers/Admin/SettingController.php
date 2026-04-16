<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    private array $keys = [
        'store_name',
        'store_email',
        'store_phone',
        'store_address',
        'tax_percent',
        'footer_text',
        // Landing Page Content
        'landing_hero_title',
        'landing_hero_description',
        'landing_solusi_text',
        'landing_solusi_desc',
        'landing_feature_1_title',
        'landing_feature_1_desc',
        'landing_feature_2_title',
        'landing_feature_2_desc',
        'landing_about_desc',
        'landing_location_photo_1',
        'landing_location_photo_2',
        'landing_location_photo_3',
    ];

    public function index()
    {
        $settings = AppSetting::valuesByKeys($this->keys);

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => 'required|string|max:150',
            'store_email' => 'required|email|max:150',
            'store_phone' => 'nullable|string|max:30',
            'store_address' => 'nullable|string|max:500',
            'tax_percent' => 'required|numeric|min:0|max:100',
            'footer_text' => 'nullable|string|max:255',
            'landing_hero_title' => 'nullable|string|max:150',
            'landing_hero_description' => 'nullable|string|max:500',
            'landing_solusi_text' => 'nullable|string|max:100',
            'landing_solusi_desc' => 'nullable|string|max:250',
            'landing_feature_1_title' => 'nullable|string|max:150',
            'landing_feature_1_desc' => 'nullable|string|max:250',
            'landing_feature_2_title' => 'nullable|string|max:150',
            'landing_feature_2_desc' => 'nullable|string|max:250',
            'landing_about_desc' => 'nullable|string|max:500',
            'landing_location_photo_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'landing_location_photo_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'landing_location_photo_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $existing = AppSetting::whereIn('key', $this->keys)->pluck('value', 'key')->toArray();
        $photoKeys = [
            'landing_location_photo_1',
            'landing_location_photo_2',
            'landing_location_photo_3',
        ];

        foreach ($photoKeys as $photoKey) {
            if ($request->hasFile($photoKey)) {
                if (!empty($existing[$photoKey])) {
                    Storage::disk('public')->delete($existing[$photoKey]);
                }

                $data[$photoKey] = $request->file($photoKey)->store('landing-location', 'public');
                continue;
            }

            $data[$photoKey] = $existing[$photoKey] ?? null;
        }

        foreach ($this->keys as $key) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $data[$key] ?? null, 'updated_by' => auth()->id()]
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
