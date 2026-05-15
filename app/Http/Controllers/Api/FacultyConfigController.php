<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\FacultyConfig;
use Illuminate\Http\Request;

class FacultyConfigController extends Controller
{
    /**
     * Lấy danh sách Khoa kèm theo Cấu hình đào tạo
     */
    public function index()
    {
        $faculties = Faculty::with('config')->get();
        return response()->json($faculties);
    }

    /**
     * Cập nhật cấu hình cho các Khoa
     */
    public function updateConfigs(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.faculty_id' => 'required|integer|exists:faculties,id',
            'configs.*.tuition_fee_per_credit' => 'required|numeric|min:0',
            'configs.*.max_credits' => 'required|integer|min:1',
        ]);

        foreach ($validated['configs'] as $conf) {
            FacultyConfig::updateOrCreate(
                ['faculty_id' => $conf['faculty_id']],
                [
                    'tuition_fee_per_credit' => $conf['tuition_fee_per_credit'],
                    'max_credits' => $conf['max_credits']
                ]
            );
        }

        return response()->json([
            'message' => 'Cập nhật cấu hình Khoa thành công',
            'data' => Faculty::with('config')->get()
        ]);
    }
}
