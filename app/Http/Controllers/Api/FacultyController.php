<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with('facultyGeneral')->get();
        return response()->json($faculties);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_general_id' => 'nullable|integer|exists:faculty_generals,id',
            'code' => 'nullable|string|max:50|unique:faculties,code',
            'name' => 'required|string|max:100',
        ]);

        $faculty = Faculty::create($validated);
        return response()->json($faculty, 201);
    }

    public function show($id)
    {
        $faculty = Faculty::with(['facultyGeneral', 'classrooms', 'teachers', 'subjects'])->findOrFail($id);
        return response()->json($faculty);
    }

    public function update(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);
        $validated = $request->validate([
            'faculty_general_id' => 'nullable|integer|exists:faculty_generals,id',
            'code' => 'sometimes|nullable|string|max:50|unique:faculties,code,' . $id,
            'name' => 'sometimes|required|string|max:100',
        ]);

        $faculty->update($validated);
        return response()->json($faculty);
    }

    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->delete();
        return response()->json(null, 204);
    }
}
