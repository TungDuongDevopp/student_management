<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{
    /**
     * Lấy danh sách cấu hình Key-Value
     */
    public function index()
    {
        // Chuyển mảng object thành mảng key-value cho dễ dùng ở frontend
        // VD: [{key: 'a', value: '1'}] => {a: '1'}
        $configs = SystemConfig::all()->pluck('value', 'key');
        return response()->json($configs);
    }

    /**
     * Cập nhật nhiều cấu hình cùng lúc
     */
    public function updateConfigs(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*' => 'nullable|string'
        ]);

        foreach ($validated['configs'] as $key => $value) {
            SystemConfig::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'message' => 'Cập nhật cấu hình hệ thống thành công',
            'data' => SystemConfig::all()->pluck('value', 'key')
        ]);
    }
}
