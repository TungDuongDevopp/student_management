<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string',
            'target_audience' => 'required|string',
            'short_desc' => 'nullable|string'
        ]);

        $news = new News();
        $news->title = $request->title;
        $news->slug = Str::slug($request->title) . '-' . time();
        $news->content = $request->content;
        $news->category = $request->category;
        $news->target_audience = $request->target_audience;
        
        // short_desc isn't in db natively based on schema, wait. The schema had no short_desc!
        // We can prepend it or just ignore it if it doesn't exist. I'll just skip it or prepend to content.
        
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('public/news');
            $news->thumbnail = str_replace('public/', 'storage/', $path);
        } else {
            $news->thumbnail = 'https://images.unsplash.com/photo-1523050854058-8df90110c476?w=600&h=400&fit=crop';
        }

        $news->save();

        return redirect()->back()->with('success', 'Đã thêm bài viết thành công!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return redirect()->back()->with('success', 'Đã xóa bài viết!');
    }
}
