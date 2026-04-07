<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $keys = [
        'store_name',
        'store_email',
        'store_phone',
        'store_address',
        'tax_percent',
        'footer_text',
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
        ]);

        foreach ($this->keys as $key) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $data[$key] ?? null, 'updated_by' => auth()->id()]
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
