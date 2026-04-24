<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with(['faculty', 'teacher', 'semester'])->get();
        return response()->json($classrooms);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|integer|exists:faculties,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'semester_id' => 'nullable|integer|exists:semesters,id',
            'code' => 'nullable|string|max:50|unique:classrooms,code',
            'quantity' => 'nullable|integer',
        ]);

        $classroom = Classroom::create($validated);
        return response()->json($classroom, 201);
    }

    public function show($id)
    {
        $classroom = Classroom::with(['faculty', 'teacher', 'semester', 'students', 'schedules'])->findOrFail($id);
        return response()->json($classroom);
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);
        $validated = $request->validate([
            'faculty_id' => 'sometimes|required|integer|exists:faculties,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'semester_id' => 'nullable|integer|exists:semesters,id',
            'code' => 'sometimes|nullable|string|max:50|unique:classrooms,code,' . $id,
            'quantity' => 'nullable|integer',
        ]);

        $classroom->update($validated);
        return response()->json($classroom);
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();
        return response()->json(null, 204);
    }
}
