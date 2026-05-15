<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'account_id'   => 'required|integer|exists:accounts,id',
            'faculty_id'   => 'required|integer|exists:faculties,id',
            'teacher_code' => 'nullable|string|max:50|unique:teachers,teacher_code',
            'name'         => 'nullable|string|max:100',
            'email'        => 'nullable|string|email|max:255',
            'images'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'date_of_birth'=> 'nullable|date',
            'gender'       => 'nullable|integer|in:0,1',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'department'   => 'nullable|string|max:100',
            'degree'       => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('images')) {
            $validated['images'] = $request->file('images')->store('images/teachers', 'public');
        }

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
            'account_id'   => 'sometimes|required|integer|exists:accounts,id',
            'faculty_id'   => 'sometimes|required|integer|exists:faculties,id',
            'teacher_code' => 'sometimes|nullable|string|max:50|unique:teachers,teacher_code,' . $id,
            'name'         => 'nullable|string|max:100',
            'email'        => 'nullable|string|email|max:255',
            'images'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'date_of_birth'=> 'nullable|date',
            'gender'       => 'nullable|integer|in:0,1',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'department'   => 'nullable|string|max:100',
            'degree'       => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('images')) {
            // Xóa ảnh cũ nếu có
            if ($teacher->images) {
                Storage::disk('public')->delete($teacher->images);
            }
            $validated['images'] = $request->file('images')->store('images/teachers', 'public');
        }

        $teacher->update($validated);
        return response()->json($teacher);
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        // Xóa ảnh khi xóa giảng viên
        if ($teacher->images) {
            Storage::disk('public')->delete($teacher->images);
        }
        $teacher->delete();
        return response()->json(null, 204);
    }
}
