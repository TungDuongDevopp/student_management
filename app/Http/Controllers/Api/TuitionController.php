<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tuition;
use Illuminate\Http\Request;

class TuitionController extends Controller
{
    public function index()
    {
        $tuitions = Tuition::with(['student.classroom', 'semester', 'payments', 'enrollments.schedule.subject'])->get();
        return response()->json($tuitions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'   => 'nullable|integer|exists:students,id',
            'semester_id'  => 'nullable|integer|exists:semesters,id',
            'total_amount' => 'nullable|numeric',
            'paid_amount'  => 'nullable|numeric',
        ]);

        $tuition = Tuition::create($validated);
        return response()->json($tuition, 201);
    }

    public function show($id)
    {
        $tuition = Tuition::with(['student.classroom', 'semester', 'payments', 'enrollments.schedule.subject'])->findOrFail($id);
        return response()->json($tuition);
    }

    public function update(Request $request, $id)
    {
        $tuition   = Tuition::findOrFail($id);
        $validated = $request->validate([
            'total_amount' => 'nullable|numeric|min:0',
            'paid_amount'  => 'nullable|numeric|min:0',
        ]);

        $tuition->update($validated);
        return response()->json($tuition->fresh(['student.classroom', 'semester', 'payments', 'enrollments.schedule.subject']));
    }

    public function destroy($id)
    {
        $tuition = Tuition::findOrFail($id);
        $tuition->delete();
        return response()->json(null, 204);
    }
}
