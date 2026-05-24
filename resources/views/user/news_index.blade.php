@extends(Auth::check() && Auth::user()->role_id == 2 ? 'layouts.user.teacher_sidebar' : 'layouts.user.student_sidebar')

@section('title', 'Tin tức & Thông báo')

@section('content')
<style>
    .news-page-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        width: calc(100% + 3rem);
        margin: -1.5rem;
        padding: 1.5rem;
        box-sizing: border-box;
    }
    .news-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .news-header h1 { margin: 0; font-size: 1.5rem; font-weight: 700; color: #0f172a; }
    
    /* Layout chia 2 cột */
    .news-layout {
        display: flex;
        gap: 2rem;
    }
    .news-main-col {
        flex: 7;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .news-sidebar-col {
        flex: 3;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        position: sticky;
        top: 1.5rem;
        height: fit-content;
    }

    /* Grid cho cột chính */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    @media (max-width: 768px) {
        .news-grid { grid-template-columns: 1fr; }
        .news-layout { flex-direction: column; }
    }

    .news-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }
    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .news-thumb {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .news-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .news-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: 0.8rem;
        color: #64748b;
    }
    .news-tag {
        background: #eff6ff;
        color: #2563eb;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .news-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 0.5rem;
        line-height: 1.4;
    }
    .news-title a { color: inherit; text-decoration: none; }
    .news-title a:hover { color: #2563eb; }
    .news-desc {
        font-size: 0.9rem;
        color: #475569;
        margin: 0;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Sidebar Styles */
    .sidebar-widget {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .widget-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #2563eb;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-posts-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .sidebar-post-item {
        display: flex;
        gap: 0.75rem;
        text-decoration: none;
        color: inherit;
        align-items: flex-start;
        transition: transform 0.2s;
    }
    .sidebar-post-item:hover {
        transform: translateX(4px);
    }
    .sidebar-post-thumb {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 6px;
        background: #f8fafc;
        flex-shrink: 0;
        border: 1px solid #e2e8f0;
    }
    .sidebar-post-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        overflow: hidden;
    }
    .sidebar-post-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .sidebar-post-item:hover .sidebar-post-title {
        color: #2563eb;
    }
    .sidebar-post-date {
        font-size: 0.75rem;
        color: #94a3b8;
    }
</style>

<div class="news-page-container">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ Auth::check() && Auth::user()->role_id == 2 ? route('teacher.home') : route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Tin tức & Thông báo</li>
        </ol>
    </nav>

    <div class="news-header">
        <h1>Tin tức & Thông báo</h1>
    </div>

    <div class="news-layout">
        <!-- Cột chính: Danh sách tin tức -->
        <main class="news-main-col">
            <div class="news-grid">
                @forelse($news as $article)
                <article class="news-card">
                    <a href="{{ route('user.news.show', $article->id) }}">
                        <img src="{{ $article->thumbnail ? asset($article->thumbnail) : 'https://upload.wikimedia.org/wikipedia/commons/2/25/Truong_Dai_hoc_Mo_Dia_chat.jpg' }}" alt="thumb" class="news-thumb">
                    </a>
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-tag">{{ $article->category ?? 'Chung' }}</span>
                            <time><i class="fa-regular fa-clock"></i> {{ $article->created_at->format('d/m/Y') }}</time>
                        </div>
                        <h3 class="news-title">
                            <a href="{{ route('user.news.show', $article->id) }}">{{ $article->title }}</a>
                        </h3>
                        <p class="news-desc">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                    </div>
                </article>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #64748b; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <i class="fa-regular fa-newspaper" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                    <p style="margin:0; font-size: 1.1rem;">Chưa có bản tin nào.</p>
                </div>
                @endforelse
            </div>
        </main>

        <!-- Cột phụ (Sidebar): Bài viết gần nhất -->
        <aside class="news-sidebar-col">
            <div class="sidebar-widget">
                <h2 class="widget-title"><i class="fa-solid fa-bolt" style="color: #f59e0b;"></i> Bài viết gần nhất</h2>
                <div class="sidebar-posts-list">
                    @forelse($news->take(5) as $recent)
                    <a href="{{ route('user.news.show', $recent->id) }}" class="sidebar-post-item">
                        <img src="{{ $recent->thumbnail ? asset($recent->thumbnail) : 'https://upload.wikimedia.org/wikipedia/commons/2/25/Truong_Dai_hoc_Mo_Dia_chat.jpg' }}" alt="recent-thumb" class="sidebar-post-thumb">
                        <div class="sidebar-post-info">
                            <h4 class="sidebar-post-title">{{ $recent->title }}</h4>
                            <span class="sidebar-post-date"><i class="fa-regular fa-clock"></i> {{ $recent->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                    @empty
                    <div style="text-align: center; color: #94a3b8; font-size: 0.85rem; padding: 1rem 0;">Không có bài viết nào gần đây.</div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
