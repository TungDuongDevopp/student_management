<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemConfig;

class AdminConfigController extends Controller
{
    /**
     * Read the current registration_enabled value from Database SystemConfig.
     */
    private function isRegistrationEnabled(): bool
    {
        // '1' means open, '0' or default null/empty means closed
        $val = SystemConfig::getValue('is_registration_open', '1');
        return $val === '1';
    }

    /**
     * Show the config page with both registration and grading toggles.
     */
    public function show()
    {
        $registrationEnabled = (SystemConfig::getValue('is_registration_open', '1') === '1');
        $gradingEnabled = (SystemConfig::getValue('is_grading_open', '0') === '1');
        // For UI: checkbox "Đóng đăng ký" checked means closed, so invert
        $isRegistrationClosed = !$registrationEnabled;
        $isGradingClosed = !$gradingEnabled;
        return view('admin.config', [
            'registrationClosed' => $isRegistrationClosed,
            'gradingClosed' => $isGradingClosed,
        ]);
    }

    /**
     * Toggle the enrollment registration feature.
     */
    public function toggleEnrollment(Request $request)
    {
        $isClosed = $request->has('enabled') && $request->boolean('enabled');
        $registrationOpenValue = $isClosed ? '0' : '1';
        SystemConfig::updateOrCreate(
            ['key' => 'is_registration_open'],
            ['value' => $registrationOpenValue]
        );
        return back()->with('status', 'Cập nhật cài đặt đăng ký thành công.');
    }

    /**
     * Toggle the grading feature.
     */
    public function toggleGrading(Request $request)
    {
        $isClosed = $request->has('grading_enabled') && $request->boolean('grading_enabled');
        $gradingOpenValue = $isClosed ? '0' : '1';
        SystemConfig::updateOrCreate(
            ['key' => 'is_grading_open'],
            ['value' => $gradingOpenValue]
        );
        return back()->with('status', 'Cập nhật cài đặt chấm điểm thành công.');
    }
}
