@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Đơn đăng ký')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Điểm số</div>
        <div class="page-header">
            <h1>Quản lý Đơn đăng ký</h1>
        </div>
        <div class="empty-state">
            <h2>Chức năng đang được phát triển</h2>
            <p>Vui lòng quay lại sau.</p>
        </div>
    </div>
@endsection
