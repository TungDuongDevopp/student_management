<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('enrollment')->get();
        return response()->json($attendances);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'nullable|integer|exists:enrollments,id',
            'schedule_session_id' => 'nullable|integer|exists:schedule_sessions,id',
            'attendance_date' => 'nullable|date',
            'status' => 'nullable|integer',
        ]);

        $attendance = Attendance::create($validated);
        return response()->json($attendance, 201);
    }

    public function show($id)
    {
        $attendance = Attendance::with('enrollment')->findOrFail($id);
        return response()->json($attendance);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $validated = $request->validate([
            'enrollment_id' => 'nullable|integer|exists:enrollments,id',
            'schedule_session_id' => 'nullable|integer|exists:schedule_sessions,id',
            'attendance_date' => 'nullable|date',
            'status' => 'nullable|integer',
        ]);

        $attendance->update($validated);
        return response()->json($attendance);
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return response()->json(null, 204);
    }
}
