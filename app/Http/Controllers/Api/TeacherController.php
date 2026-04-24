<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['account', 'faculty'])->get();
        return response()->json($teachers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'faculty_id' => 'required|integer|exists:faculties,id',
            'teacher_code' => 'nullable|string|max:50|unique:teachers,teacher_code',
            'name' => 'nullable|string|max:100',
        ]);

        $teacher = Teacher::create($validated);
        return response()->json($teacher, 201);
    }

    public function show($id)
    {
        $teacher = Teacher::with(['account', 'faculty', 'schedules', 'classrooms'])->findOrFail($id);
        return response()->json($teacher);
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $validated = $request->validate([
            'account_id' => 'sometimes|required|integer|exists:accounts,id',
            'faculty_id' => 'sometimes|required|integer|exists:faculties,id',
            'teacher_code' => 'sometimes|nullable|string|max:50|unique:teachers,teacher_code,' . $id,
            'name' => 'nullable|string|max:100',
        ]);

        $teacher->update($validated);
        return response()->json($teacher);
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return response()->json(null, 204);
    }
}
