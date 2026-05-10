@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Phản hồi')

@section('content')
<style>
    .creative-card {
        background: var(--bg-card, #fff);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid var(--sidebar-border, #e2e8f0);
    }
    .creative-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--sidebar-hover-bg, #f1f5f9);
    }
    .creative-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--sidebar-active-bg, #eff6ff);
        color: var(--sidebar-active-text, #2563eb);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .creative-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-title, #0f172a);
        margin: 0;
    }
    .creative-body {
        min-height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-main, #64748b);
        text-align: center;
        gap: 1rem;
    }
    .creative-btn {
        background: var(--sidebar-active-text, #2563eb);
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }
    .creative-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
</style>

<div class="creative-card">
    <div class="creative-header">
        <div class="creative-icon">
            <i class="fa-solid fa-comments"></i>
        </div>
        <h1 class="creative-title">Quản lý Phản hồi</h1>
    </div>
    
    <div class="creative-body">
        <i class="fa-solid fa-wand-magic-sparkles" style="font-size: 3rem; color: #cbd5e1;"></i>
        <h3>Giao diện đang được xây dựng</h3>
        <p>Phần <strong>Quản lý Phản hồi</strong> sẽ được cập nhật giao diện chi tiết sau. Hiện tại đã tích hợp thành công Sidebar.</p>
        <button class="creative-btn"><i class="fa-solid fa-plus"></i> Thêm dữ liệu mới</button>
    </div>
</div>
@endsection