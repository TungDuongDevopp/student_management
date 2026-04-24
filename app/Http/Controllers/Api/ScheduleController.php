<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['subject', 'teacher', 'room', 'semester', 'classroom'])->get();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'semester_id' => 'nullable|integer|exists:semesters,id',
            'classroom_id' => 'nullable|integer|exists:classrooms,id',
            'day_of_week' => 'nullable|integer|min:1|max:7',
            'shift' => 'nullable|integer',
        ]);

        $schedule = Schedule::create($validated);
        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        $schedule = Schedule::with(['subject', 'teacher', 'room', 'semester', 'classroom', 'enrollments'])->findOrFail($id);
        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $validated = $request->validate([
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'semester_id' => 'nullable|integer|exists:semesters,id',
            'classroom_id' => 'nullable|integer|exists:classrooms,id',
            'day_of_week' => 'nullable|integer|min:1|max:7',
            'shift' => 'nullable|integer',
        ]);

        $schedule->update($validated);
        return response()->json($schedule);
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(null, 204);
    }
}
