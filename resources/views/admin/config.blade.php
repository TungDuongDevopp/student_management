@extends('layouts.admin.sidebar')
@section('title', 'Cài đặt hệ thống')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin-shared.css') }}">

    <div class="content-wrapper">
        <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Cài đặt hệ thống</div>
        <div class="page-header">
            <h1>Cài đặt hệ thống</h1>
        </div>
        <div class="empty-state">
            <h2>Chức năng đang được phát triển</h2>
            <p>Vui lòng quay lại sau.</p>
        </div>
    </div>
@endsection
