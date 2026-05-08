@extends('layouts.admin.sidebar')
@section('title', 'Quản lý Học phí')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Quản lý Học phí</div>
        <div class="page-header">
            <h1>Quản lý Học phí</h1>
        </div>
        <div class="empty-state">
            <h2>Chức năng đang được phát triển</h2>
            <p>Vui lòng quay lại sau.</p>
        </div>
    </div>
@endsection
