{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Dashboard Admin</h1>
                <p>Selamat datang, {{ Auth::user()->name }}!</p>
                <p>Role: {{ Auth::user()->role }}</p>

                {{-- Konten admin di sini --}}
                <div class="card">
                    <div class="card-header">
                        Statistik
                    </div>
                    <div class="card-body">
                        <p>Ini adalah dashboard untuk admin.</p>
                        {{-- Tambahkan widget admin --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
