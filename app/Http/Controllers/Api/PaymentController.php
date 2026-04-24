<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('tuition')->get();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tuition_id' => 'nullable|integer|exists:tuitions,id',
            'amount' => 'nullable|numeric',
            'payment_date' => 'nullable|date',
        ]);

        $payment = Payment::create($validated);
        return response()->json($payment, 201);
    }

    public function show($id)
    {
        $payment = Payment::with('tuition')->findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $validated = $request->validate([
            'tuition_id' => 'nullable|integer|exists:tuitions,id',
            'amount' => 'nullable|numeric',
            'payment_date' => 'nullable|date',
        ]);

        $payment->update($validated);
        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(null, 204);
    }
}
