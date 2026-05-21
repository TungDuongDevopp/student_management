<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with([
            'student.classroom',
            'schedule.subject',
            'schedule.semester',
            'schedule.teacher',
            'grade',
        ])->get();
        return response()->json($enrollments);
    }

    public function store(Request $request)
    {
        // Check registration status from Database SystemConfig
        $enabled = (\App\Models\SystemConfig::getValue('is_registration_open', '1') === '1');
        if (!$enabled) {
            return response()->json(['success' => false, 'message' => 'Đăng ký môn học đã đóng. Vui lòng thử lại sau.'], 403);
        }

        $validated = $request->validate([
            'student_id'  => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'score_c'     => 'nullable|numeric|min:0|max:10',
            'score_b'     => 'nullable|numeric|min:0|max:10',
            'score_a'     => 'nullable|numeric|min:0|max:10',
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
            
            if (isset($validated['score_c']) || isset($validated['score_b']) || isset($validated['score_a'])) {
                $enrollment->grade()->create([
                    'score_c' => $validated['score_c'] ?? null,
                    'score_b' => $validated['score_b'] ?? null,
                    'score_a' => $validated['score_a'] ?? null,
                ]);
            }
            
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
            'grade',
        ])->findOrFail($id);
        return response()->json($enrollment);
    }

    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $validated  = $request->validate([
            'score_c'     => 'nullable|numeric|min:0|max:10',
            'score_b'     => 'nullable|numeric|min:0|max:10',
            'score_a'     => 'nullable|numeric|min:0|max:10',
            'status'      => 'nullable|integer',
        ]);

        $enrollment->update($validated);
        
        if (isset($validated['score_c']) || isset($validated['score_b']) || isset($validated['score_a'])) {
            $enrollment->grade()->updateOrCreate(
                ['enrollment_id' => $enrollment->id],
                [
                    'score_c' => $validated['score_c'] ?? $enrollment->grade?->score_c,
                    'score_b' => $validated['score_b'] ?? $enrollment->grade?->score_b,
                    'score_a' => $validated['score_a'] ?? $enrollment->grade?->score_a,
                ]
            );
        }

        return response()->json($enrollment->fresh([
            'student.classroom',
            'schedule.subject',
            'schedule.semester',
            'schedule.teacher',
            'grade',
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
