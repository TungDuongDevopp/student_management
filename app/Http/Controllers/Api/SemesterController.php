<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::all();
        return response()->json($semesters);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
        ]);

        $semester = Semester::create($validated);
        return response()->json($semester, 201);
    }

    public function show($id)
    {
        $semester = Semester::with(['schedules', 'tuitions'])->findOrFail($id);
        return response()->json($semester);
    }

    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);
        $validated = $request->validate([
            'name' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
        ]);

        $semester->update($validated);
        return response()->json($semester);
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();
        return response()->json(null, 204);
    }
}
