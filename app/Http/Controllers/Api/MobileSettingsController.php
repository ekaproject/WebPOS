<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;

class MobileSettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = AppSetting::valuesByKeys([
            'store_name',
            'store_address',
            'cashier_name',
        ]);

        return response()->json([
            'store_name'    => $settings['store_name']    ?? 'POS',
            'store_address' => $settings['store_address'] ?? '',
            'cashier_name'  => $settings['cashier_name']  ?? '',
        ]);
    }
}
