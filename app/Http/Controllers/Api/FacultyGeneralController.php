<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FacultyGeneral;
use Illuminate\Http\Request;

class FacultyGeneralController extends Controller
{
    public function index()
    {
        $generals = FacultyGeneral::all();
        return response()->json($generals);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:faculty_generals,name',
            'max_credits' => 'required|integer|min:1',
            'tuition_fee_per_credit' => 'required|numeric|min:0',
        ]);

        $general = FacultyGeneral::create($validated);
        return response()->json($general, 201);
    }

    public function show($id)
    {
        $general = FacultyGeneral::findOrFail($id);
        return response()->json($general);
    }

    public function update(Request $request, $id)
    {
        $general = FacultyGeneral::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100|unique:faculty_generals,name,' . $id,
            'max_credits' => 'sometimes|required|integer|min:1',
            'tuition_fee_per_credit' => 'sometimes|required|numeric|min:0',
        ]);

        $general->update($validated);
        return response()->json($general);
    }

    public function destroy($id)
    {
        $general = FacultyGeneral::findOrFail($id);
        $general->delete();
        return response()->json(null, 204);
    }
}
