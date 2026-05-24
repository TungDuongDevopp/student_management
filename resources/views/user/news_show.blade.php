@extends(Auth::check() && Auth::user()->role_id == 2 ? 'layouts.user.teacher_sidebar' : 'layouts.user.student_sidebar')

@section('title', $article->title)

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
    .breadcrumb-nav { margin-bottom: 0.5rem; }
    .news-layout {
        display: flex;
        gap: 2rem;
    }
    .news-main-col {
        flex: 7;
        min-width: 0;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        padding: 2rem;
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

    .article-header {
        margin-bottom: 2rem;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 1.5rem;
    }
    .article-tags {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .article-tag {
        background: #eff6ff;
        color: #2563eb;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .article-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 1rem;
        line-height: 1.3;
    }
    .article-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        font-size: 0.9rem;
        color: #64748b;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .article-content {
        font-size: 1.05rem;
        color: #334155;
        line-height: 1.8;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
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
    
    @media (max-width: 768px) {
        .news-layout { flex-direction: column; }
    }
</style>

<div class="news-page-container">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li><a href="{{ Auth::check() && Auth::user()->role_id == 2 ? route('teacher.home') : route('student.home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li><a href="{{ route('user.news.index') }}">Tin tức & Thông báo</a></li>
            <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
            <li class="active">Chi tiết bài viết</li>
        </ol>
    </nav>

    <div class="news-layout">
        <!-- Cột chính: Chi tiết bài viết -->
        <main class="news-main-col">
            <header class="article-header">
                <div class="article-tags">
                    <span class="article-tag">{{ $article->category ?? 'Chung' }}</span>
                </div>
                <h1 class="article-title">{{ $article->title }}</h1>
                <div class="article-meta">
                    <div class="meta-item">
                        <i class="fa-regular fa-user" style="color:#2563eb;"></i> Admin Hệ Thống
                    </div>
                    <div class="meta-item">
                        <i class="fa-regular fa-clock" style="color:#64748b;"></i> {{ $article->created_at->format('d/m/Y - H:i') }}
                    </div>
                </div>
            </header>

            <article class="article-content">
                {!! $article->content !!}
            </article>
        </main>

        <!-- Cột phụ (Sidebar): Bài viết khác -->
        <aside class="news-sidebar-col">
            <div class="sidebar-widget">
                <h2 class="widget-title"><i class="fa-solid fa-bolt" style="color: #f59e0b;"></i> Tin tức mới nhất</h2>
                <div class="sidebar-posts-list">
                    @forelse($recentNews as $recent)
                    <a href="{{ route('user.news.show', $recent->id) }}" class="sidebar-post-item">
                        <img src="{{ $recent->thumbnail ? asset($recent->thumbnail) : 'https://upload.wikimedia.org/wikipedia/commons/2/25/Truong_Dai_hoc_Mo_Dia_chat.jpg' }}" alt="recent-thumb" class="sidebar-post-thumb">
                        <div class="sidebar-post-info">
                            <h4 class="sidebar-post-title">{{ $recent->title }}</h4>
                            <span class="sidebar-post-date"><i class="fa-regular fa-clock"></i> {{ $recent->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                    @empty
                    <div style="text-align: center; color: #94a3b8; font-size: 0.85rem; padding: 1rem 0;">Không có bài viết nào khác.</div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
