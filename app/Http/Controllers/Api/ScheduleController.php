<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ScheduleSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    private array $relations = ['subject', 'teacher', 'room', 'semester', 'sessions'];

    public function index()
    {
        $schedules = Schedule::with($this->relations)
            ->withCount('enrollments')
            ->get();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id'              => 'nullable|integer|exists:subjects,id',
            'teacher_id'              => 'nullable|integer|exists:teachers,id',
            'room_id'                 => 'nullable|integer|exists:rooms,id',
            'semester_id'             => 'nullable|integer|exists:semesters,id',
            'group_code'              => 'nullable|string|max:20',
            'max_capacity'            => 'nullable|integer|min:1',
            'sessions'                => 'nullable|array',
            'sessions.*.day_of_week'  => 'nullable|integer|min:2|max:8',
            'sessions.*.start_time'   => 'nullable|date_format:H:i',
            'sessions.*.end_time'     => 'nullable|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $schedule = Schedule::create(collect($validated)->except('sessions')->toArray());

            foreach ($validated['sessions'] ?? [] as $sess) {
                $schedule->sessions()->create($sess);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(
            Schedule::with($this->relations)->withCount('enrollments')->find($schedule->id),
            201
        );
    }

    public function show($id)
    {
        $schedule = Schedule::with(array_merge($this->relations, ['enrollments.student']))
            ->findOrFail($id);
        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $schedule  = Schedule::findOrFail($id);
        $validated = $request->validate([
            'subject_id'              => 'nullable|integer|exists:subjects,id',
            'teacher_id'              => 'nullable|integer|exists:teachers,id',
            'room_id'                 => 'nullable|integer|exists:rooms,id',
            'semester_id'             => 'nullable|integer|exists:semesters,id',
            'group_code'              => 'nullable|string|max:20',
            'max_capacity'            => 'nullable|integer|min:1',
            'sessions'                => 'nullable|array',
            'sessions.*.day_of_week'  => 'nullable|integer|min:2|max:8',
            'sessions.*.start_time'   => 'nullable|date_format:H:i',
            'sessions.*.end_time'     => 'nullable|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $schedule->update(collect($validated)->except('sessions')->toArray());

            // Xóa sessions cũ, tạo lại
            $schedule->sessions()->delete();
            foreach ($validated['sessions'] ?? [] as $sess) {
                $schedule->sessions()->create($sess);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(
            Schedule::with($this->relations)->withCount('enrollments')->find($id)
        );
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete(); // sessions bị cascade delete
        return response()->json(null, 204);
    }
}
