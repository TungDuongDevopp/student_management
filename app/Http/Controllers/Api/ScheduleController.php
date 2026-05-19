<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ScheduleSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    private array $relations = ['subject', 'teacher', 'semester', 'sessions.room'];

    public function index()
    {
        $schedules = Schedule::with($this->relations)
            ->withCount('enrollments')
            ->get();
        return response()->json($schedules);
    }

    private function checkScheduleConflicts(array $sessions, $semesterId, $teacherId, $startDate, $endDate, $excludeScheduleId = null)
    {
        foreach ($sessions as $idx => $sess) {
            $dayOfWeek = $sess['day_of_week'] ?? null;
            $startTime = $sess['start_time'] ?? null;
            $endTime = $sess['end_time'] ?? null;
            $roomId = $sess['room_id'] ?? null;

            if (!$dayOfWeek || !$startTime || !$endTime) {
                continue;
            }

            // 1. Check Room Conflict
            if ($roomId) {
                $roomConflict = ScheduleSession::where('room_id', $roomId)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime)
                    ->whereHas('schedule', function ($q) use ($semesterId, $startDate, $endDate, $excludeScheduleId) {
                        if ($excludeScheduleId) {
                            $q->where('id', '!=', $excludeScheduleId);
                        }
                        $q->where(function ($sub) use ($semesterId, $startDate, $endDate) {
                            $sub->where('semester_id', $semesterId);
                            if ($startDate && $endDate) {
                                $sub->orWhere(function ($dates) use ($startDate, $endDate) {
                                    $dates->where('start_date', '<=', $endDate)
                                          ->where('end_date', '>=', $startDate);
                                });
                            }
                        });
                    })->first();

                if ($roomConflict) {
                    $room = \App\Models\Room::find($roomId);
                    $conflictSchedule = $roomConflict->schedule;
                    $conflictSubjectName = $conflictSchedule->subject->name ?? 'Môn học khác';
                    $conflictGroupCode = $conflictSchedule->group_code ? " (Nhóm {$conflictSchedule->group_code})" : "";
                    
                    $dayNames = [
                        2 => 'Thứ 2', 3 => 'Thứ 3', 4 => 'Thứ 4', 
                        5 => 'Thứ 5', 6 => 'Thứ 6', 7 => 'Thứ 7', 8 => 'Chủ Nhật'
                    ];
                    $dayName = $dayNames[$dayOfWeek] ?? "Thứ {$dayOfWeek}";
                    $startFmt = substr($roomConflict->start_time, 0, 5);
                    $endFmt = substr($roomConflict->end_time, 0, 5);
                    throw new \Exception("Phòng {$room->block}.{$room->name} đã bị trùng lịch vào {$dayName} ({$startFmt} - {$endFmt}) với lớp {$conflictSubjectName}{$conflictGroupCode}.");
                }
            }

            // 2. Check Teacher Conflict
            if ($teacherId) {
                $teacherConflict = ScheduleSession::where('day_of_week', $dayOfWeek)
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime)
                    ->whereHas('schedule', function ($q) use ($teacherId, $semesterId, $startDate, $endDate, $excludeScheduleId) {
                        $q->where('teacher_id', $teacherId);
                        if ($excludeScheduleId) {
                            $q->where('id', '!=', $excludeScheduleId);
                        }
                        $q->where(function ($sub) use ($semesterId, $startDate, $endDate) {
                            $sub->where('semester_id', $semesterId);
                            if ($startDate && $endDate) {
                                $sub->orWhere(function ($dates) use ($startDate, $endDate) {
                                    $dates->where('start_date', '<=', $endDate)
                                          ->where('end_date', '>=', $startDate);
                                });
                            }
                        });
                    })->first();

                if ($teacherConflict) {
                    $teacher = \App\Models\Teacher::find($teacherId);
                    $conflictSchedule = $teacherConflict->schedule;
                    $conflictSubjectName = $conflictSchedule->subject->name ?? 'Môn học khác';
                    $conflictGroupCode = $conflictSchedule->group_code ? " (Nhóm {$conflictSchedule->group_code})" : "";
                    
                    $dayNames = [
                        2 => 'Thứ 2', 3 => 'Thứ 3', 4 => 'Thứ 4', 
                        5 => 'Thứ 5', 6 => 'Thứ 6', 7 => 'Thứ 7', 8 => 'Chủ Nhật'
                    ];
                    $dayName = $dayNames[$dayOfWeek] ?? "Thứ {$dayOfWeek}";
                    $startFmt = substr($teacherConflict->start_time, 0, 5);
                    $endFmt = substr($teacherConflict->end_time, 0, 5);
                    throw new \Exception("Giảng viên {$teacher->name} đã bị trùng lịch dạy vào {$dayName} ({$startFmt} - {$endFmt}) với lớp {$conflictSubjectName}{$conflictGroupCode}.");
                }
            }
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id'              => 'nullable|integer|exists:subjects,id',
            'teacher_id'              => 'nullable|integer|exists:teachers,id',
            'semester_id'             => 'nullable|integer|exists:semesters,id',
            'group_code'              => 'nullable|string|max:20',
            'max_capacity'            => 'nullable|integer|min:1',
            'start_date'              => 'nullable|date',
            'end_date'                => 'nullable|date|after_or_equal:start_date',
            'sessions'                => 'nullable|array',
            'sessions.*.room_id'      => 'nullable|integer|exists:rooms,id',
            'sessions.*.day_of_week'  => 'nullable|integer|min:2|max:8',
            'sessions.*.start_time'   => 'nullable|date_format:H:i',
            'sessions.*.end_time'     => 'nullable|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $this->checkScheduleConflicts(
                $validated['sessions'] ?? [],
                $validated['semester_id'],
                $validated['teacher_id'] ?? null,
                $validated['start_date'] ?? null,
                $validated['end_date'] ?? null
            );

            $schedule = Schedule::create(collect($validated)->except('sessions')->toArray());

            foreach ($validated['sessions'] ?? [] as $sess) {
                $schedule->sessions()->create($sess);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
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
            'semester_id'             => 'nullable|integer|exists:semesters,id',
            'group_code'              => 'nullable|string|max:20',
            'max_capacity'            => 'nullable|integer|min:1',
            'start_date'              => 'nullable|date',
            'end_date'                => 'nullable|date|after_or_equal:start_date',
            'sessions'                => 'nullable|array',
            'sessions.*.room_id'      => 'nullable|integer|exists:rooms,id',
            'sessions.*.day_of_week'  => 'nullable|integer|min:2|max:8',
            'sessions.*.start_time'   => 'nullable|date_format:H:i',
            'sessions.*.end_time'     => 'nullable|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $this->checkScheduleConflicts(
                $validated['sessions'] ?? [],
                $validated['semester_id'],
                $validated['teacher_id'] ?? null,
                $validated['start_date'] ?? null,
                $validated['end_date'] ?? null,
                $id
            );

            $schedule->update(collect($validated)->except('sessions')->toArray());

            $schedule->sessions()->delete();
            foreach ($validated['sessions'] ?? [] as $sess) {
                $schedule->sessions()->create($sess);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(
            Schedule::with($this->relations)->withCount('enrollments')->find($id)
        );
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(null, 204);
    }
}
