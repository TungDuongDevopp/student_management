<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    private array $relations = ['subject', 'teacher', 'room', 'semester', 'classroom'];

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
            'subject_id'   => 'nullable|integer|exists:subjects,id',
            'teacher_id'   => 'nullable|integer|exists:teachers,id',
            'room_id'      => 'nullable|integer|exists:rooms,id',
            'semester_id'  => 'nullable|integer|exists:semesters,id',
            'classroom_id' => 'nullable|integer|exists:classrooms,id',
            'day_of_week'  => 'nullable|integer|min:2|max:8',
            'shift'        => 'nullable|integer|min:1',
        ]);

        $schedule = Schedule::create($validated);
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
            'subject_id'   => 'nullable|integer|exists:subjects,id',
            'teacher_id'   => 'nullable|integer|exists:teachers,id',
            'room_id'      => 'nullable|integer|exists:rooms,id',
            'semester_id'  => 'nullable|integer|exists:semesters,id',
            'classroom_id' => 'nullable|integer|exists:classrooms,id',
            'day_of_week'  => 'nullable|integer|min:2|max:8',
            'shift'        => 'nullable|integer|min:1',
        ]);

        $schedule->update($validated);
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
