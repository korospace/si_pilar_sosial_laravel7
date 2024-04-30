@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/adminlte/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/showLoadingSpinner.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/toastify.min.css') }}">
    <style>
        body {
            background: #F4F6F9;
        }
        .form-group {
            position: relative;
        }
        .invalid-feedback {
            position: absolute;
            bottom: -20px;
        }
        .page-item.active .page-link {
            background-color: #6c757d;
            border-color: #6c757d;
        }
    </style>

    @stack('dashboard-wraper.css')
@endpush

@push('jscript')
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/adminlte/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.cookie.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/plugins/showLoadingSpinner.js') }}"></script>
    <script src="{{ asset('js/plugins/showToastify.js') }}"></script>
    @stack('dashboard-wraper.jscript')
@endpush

@section('content')
<body>
    <div class="wrapper">
        @include('components/dashboard-nav')

        @include('components/dashboard-aside')

        <div class="content-wrapper">
            @yield('dashboard-wraper.content')
        </div>
    </div>
</body>
@endsection
