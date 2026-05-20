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

    public function approve($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Giao dịch này đã được duyệt trước đó.'], 400);
        }

        $payment->status = 'completed';
        $payment->save();

        $tuition = \App\Models\Tuition::find($payment->tuition_id);
        if ($tuition) {
            $tuition->paid_amount = min($tuition->total_amount, $tuition->paid_amount + $payment->amount);
            $tuition->save();
        }

        return response()->json(['success' => true, 'message' => 'Duyệt thanh toán thành công!']);
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Không thể từ chối giao dịch đã được duyệt.'], 400);
        }

        $payment->status = 'failed';
        $payment->save();

        return response()->json(['success' => true, 'message' => 'Từ chối giao dịch thành công.']);
    }
}
