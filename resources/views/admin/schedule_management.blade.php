@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Lịch học')
@section('content')
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e293b;
            --bg-input: #0f172a;
            --border: #334155;
            --accent: #3b82f6;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
        }

        .content-wrapper {
            background: var(--bg-primary);
            color: var(--text);
            padding: 2rem;
            border-radius: 12px;
            min-height: calc(100vh - 4rem);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .breadcrumb {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .breadcrumb a {
            color: var(--accent);
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: var(--text-muted);
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
    </style>

    <div class="content-wrapper">
        <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Lịch học</div>
        <div class="page-header">
            <h1>Quản lý Lịch học</h1>
        </div>
        <div class="empty-state">
            <h2>Chức năng đang được phát triển</h2>
            <p>Vui lòng quay lại sau.</p>
        </div>
    </div>
@endsection
