<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('faculty')->get();
        return response()->json($subjects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'nullable|integer|exists:faculties,id',
            'code'       => 'nullable|string|max:20|unique:subjects,code',
            'name'       => 'nullable|string|max:100',
            'credits'    => 'nullable|integer',
        ]);

        $subject = Subject::create($validated);
        return response()->json($subject, 201);
    }

    public function show($id)
    {
        $subject = Subject::with(['faculty', 'schedules'])->findOrFail($id);
        return response()->json($subject);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $validated = $request->validate([
            'faculty_id' => 'nullable|integer|exists:faculties,id',
            'code'       => 'nullable|string|max:20|unique:subjects,code,' . $id,
            'name'       => 'nullable|string|max:100',
            'credits'    => 'nullable|integer',
        ]);

        $subject->update($validated);
        return response()->json($subject);
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return response()->json(null, 204);
    }
}
