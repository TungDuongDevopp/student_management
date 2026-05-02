<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['account', 'classroom'])->get();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'classroom_id' => 'nullable|integer|exists:classrooms,id',
            'student_code' => 'nullable|string|max:50|unique:students,student_code',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|string|email|max:255',
            'images' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('images')) {
            $validated['images'] = $request->file('images')->store('images/students', 'public');
        }

        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    public function show($id)
    {
        $student = Student::with(['account', 'classroom', 'enrollments', 'tuitions'])->findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $validated = $request->validate([
            'account_id' => 'sometimes|required|integer|exists:accounts,id',
            'classroom_id' => 'nullable|integer|exists:classrooms,id',
            'student_code' => 'sometimes|nullable|string|max:50|unique:students,student_code,' . $id,
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|string|email|max:255',
            'images' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('images')) {
            // Xóa ảnh cũ nếu có
            if ($student->images) {
                Storage::disk('public')->delete($student->images);
            }
            $validated['images'] = $request->file('images')->store('images/students', 'public');
        }

        $student->update($validated);
        return response()->json($student);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        // Xóa ảnh khi xóa sinh viên
        if ($student->images) {
            Storage::disk('public')->delete($student->images);
        }
        $student->delete();
        return response()->json(null, 204);
    }
}
