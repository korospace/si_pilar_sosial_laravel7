@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
@endpush

@push('dashboard-wraper.jscript')
@endpush

@section('dashboard-wraper.content')
    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
                <div class="col">
                    <div class="card card-secondary card-outline">
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col" style="max-width: 700px;">
                                    <h1 class="text-bold" style="color: #343A40;">Selamat Datang di</h1>
                                    <h1 class="text-bold text-secondary">Dashboard SI PILAR SOSIAL</h1>
                                    <p class="mt-3 text-secondary">Website pencatatan data untuk organisasi Karang Taruna, LKS, TKSK, dan PSM di Provinsi Jakarta. Kelola data dengan mudah, dengan harapan meningkatkan pemberdayaan komunitas sosial di wilayah ini.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
