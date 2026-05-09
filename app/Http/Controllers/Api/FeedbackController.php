<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('account');

        // Lọc theo trạng thái nếu có
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $feedbacks = $query->latest()->get();
        return response()->json($feedbacks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'nullable|integer|exists:accounts,id',
            'content'    => 'nullable|string',
        ]);

        // Khi tạo mới, mặc định status = 0 (chưa xem)
        $validated['status'] = Feedback::STATUS_UNREAD;

        $feedback = Feedback::create($validated);
        return response()->json($feedback, 201);
    }

    public function show($id)
    {
        $feedback = Feedback::with('account')->findOrFail($id);
        return response()->json($feedback);
    }

    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $validated = $request->validate([
            'account_id' => 'nullable|integer|exists:accounts,id',
            'content'    => 'nullable|string',
            'reply'      => 'nullable|string',
            'status'     => 'nullable|integer|in:0,1',
        ]);

        $feedback->update($validated);
        return response()->json($feedback);
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return response()->json(null, 204);
    }

    /**
     * Admin phản hồi feedback và tự động đánh dấu là đã xem
     */
    public function reply(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        $validated = $request->validate([
            'reply' => 'required|string',
        ]);

        $feedback->update([
            'reply'  => $validated['reply'],
            'status' => Feedback::STATUS_READ,
        ]);

        return response()->json([
            'message'  => 'Phản hồi đã được gửi thành công.',
            'feedback' => $feedback,
        ]);
    }

    /**
     * Đánh dấu feedback là đã xem (không cần reply)
     */
    public function markAsSeen($id)
    {
        $feedback = Feedback::findOrFail($id);

        $feedback->update([
            'status' => Feedback::STATUS_READ,
        ]);

        return response()->json([
            'message'  => 'Feedback đã được đánh dấu là đã xem.',
            'feedback' => $feedback,
        ]);
    }
}
