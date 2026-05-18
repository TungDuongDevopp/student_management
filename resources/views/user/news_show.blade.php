<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; padding: 2rem; color: #334155; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; color: #2563eb; text-decoration: none; font-weight: 600; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .back-btn:hover { text-decoration: underline; }
        .news-category { display: inline-block; padding: 0.35rem 0.75rem; background: #e0e7ff; color: #4338ca; border-radius: 6px; font-size: 0.8rem; font-weight: 700; margin-bottom: 1rem; }
        .news-title { font-size: 1.85rem; font-weight: 800; color: #0f172a; margin: 0 0 1rem; line-height: 1.3; }
        .news-meta { display: flex; gap: 1rem; color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; }
        .news-thumb { width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 2rem; }
        .news-content { line-height: 1.7; font-size: 1.05rem; color: #334155; }
    </style>
</head>
<body>
    <div class="container">
        <a href="javascript:history.back()" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
        <div>
            <span class="news-category">{{ $article->category ?? 'Thông báo chung' }}</span>
        </div>
        <h1 class="news-title">{{ $article->title }}</h1>
        <div class="news-meta">
            <span><i class="fa-regular fa-clock"></i> {{ $article->created_at->format('d/m/Y H:i') }}</span>
            <span><i class="fa-regular fa-eye"></i> Lượt xem: {{ rand(50, 500) }}</span>
        </div>
        @if($article->thumbnail)
            <img src="{{ asset($article->thumbnail) }}" alt="Thumbnail" class="news-thumb">
        @endif
        <div class="news-content">
            {!! nl2br(e($article->content)) !!}
        </div>
    </div>
</body>
</html>
