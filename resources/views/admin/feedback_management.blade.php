@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Phản hồi')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Phản hồi</div>
        <div class="page-header">
            <h1>Quản lý Phản hồi</h1>
        </div>
        <div class="empty-state">
            <h2>Chức năng đang được phát triển</h2>
            <p>Vui lòng quay lại sau.</p>
        </div>
    </div>
@endsection
