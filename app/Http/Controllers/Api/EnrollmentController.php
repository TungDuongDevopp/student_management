<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with([
            'student.classroom',
            'schedule.subject',
            'schedule.semester',
            'schedule.teacher',
        ])->get();
        return response()->json($enrollments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'  => 'required|integer|exists:students,id',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'final_score' => 'nullable|numeric',
            'status'      => 'nullable|integer',
        ]);

        $enrollment = Enrollment::create($validated);
        return response()->json($enrollment, 201);
    }

    public function show($id)
    {
        $enrollment = Enrollment::with([
            'student.classroom',
            'schedule.subject',
            'schedule.semester',
            'schedule.teacher',
            'attendances',
        ])->findOrFail($id);
        return response()->json($enrollment);
    }

    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $validated  = $request->validate([
            'final_score' => 'nullable|numeric|min:0|max:10',
            'status'      => 'nullable|integer',
        ]);

        $enrollment->update($validated);
        return response()->json($enrollment->fresh([
            'student.classroom',
            'schedule.subject',
            'schedule.semester',
            'schedule.teacher',
        ]));
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();
        return response()->json(null, 204);
    }
}
