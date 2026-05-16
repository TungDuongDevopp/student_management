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
            'final_score' => 'nullable|numeric|min:0|max:10',
            'status'      => 'nullable|integer',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $schedule = \App\Models\Schedule::lockForUpdate()->findOrFail($validated['schedule_id']);
            
            if ($schedule->max_capacity && $schedule->current_capacity >= $schedule->max_capacity) {
                \Illuminate\Support\Facades\DB::rollBack();
                return response()->json(['message' => 'Lớp học phần này đã đạt số lượng đăng ký tối đa.'], 400);
            }

            $exists = Enrollment::where('student_id', $validated['student_id'])
                ->where('schedule_id', $validated['schedule_id'])
                ->exists();
                
            if ($exists) {
                \Illuminate\Support\Facades\DB::rollBack();
                return response()->json(['message' => 'Sinh viên đã đăng ký lớp học phần này.'], 400);
            }

            $enrollment = Enrollment::create($validated);
            $schedule->increment('current_capacity');
            
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => 'Lỗi khi đăng ký: ' . $e->getMessage()], 500);
        }

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
        
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $schedule_id = $enrollment->schedule_id;
            $enrollment->delete();
            \App\Models\Schedule::where('id', $schedule_id)->where('current_capacity', '>', 0)->decrement('current_capacity');
            
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => 'Lỗi khi hủy đăng ký: ' . $e->getMessage()], 500);
        }

        return response()->json(null, 204);
    }
}
