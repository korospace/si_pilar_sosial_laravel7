@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/homepage-articles.css') }}">
@endpush

@push('jscript')
    <script src="{{ asset('js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('js/pages/homepage-articles.js') }}"></script>
@endpush

@section('content')
    {{-- ---------------- NAVBAR ------------------------ --}}
    <nav class="shadow">
        <div id="nav_section_1">
            <div id="logo_wraper" class="d-flex justify-content-center align-items-center">
                <img class="img_prov_dki" src="{{ asset('images/logo-prov-jakarta.webp') }}" alt="logo provinsi dki jakarta">
                <div class="logo_title">
                    <span>SI PILAR SOSIAL</span>
                </div>
                <img class="img_dinsos_dki" src="{{ asset('images/logo-dinassosial-jakarta.webp') }}" alt="logo dinas sosial dki jakarta">
            </div>
        </div>
        <div id="nav_section_2" class="d-flex justify-content-center align-items-center py-2">
            <a
              href=""
              class="text-white">
                <i class="fab fa-facebook pr-1"></i>
                facebook
            </a>
            <a
              href=""
              class="text-white">
                <i class="fab fa-twitter pr-1"></i>
                twitter
            </a>
            <a
              href=""
              class="text-white">
                <i class="fab fa-instagram pr-1"></i>
                instagram
            </a>
            <a
              href=""
              class="text-white">
                <i class="fab fa-youtube pr-1"></i>
                youtube
            </a>
        </div>
    </nav>
    {{-- ---------------- END NAVBAR -------------------- --}}

    {{-- ---------------- Daftar Berita ---------------- --}}
    <section id="daftar_berita" class="d-flex align-items-center" style="flex-direction: column;">
        <div id="title_wraper" class="row container">
            <div class="col-12">
                <h2 id="title" class="">
                    Daftar Berita
                    <a href="{{ route('homepage.main') }}">Beranda »</a>
                </h2>
            </div>
        </div>

        <div id="search_wraper" class="row container mt-5">
            <div class="col-md-8 col-lg-6 col-xl-4" style="position: relative;display: flex;align-items: center;">
                <input type="text" name="search" id="search" class="form-control" placeholder="cari berita ..." style="padding-right: 36px;"/>
                <i class="fas fa-search" style="position: absolute;right: 26px;"></i>
            </div>
        </div>

        <div id="card_wraper" class="row container mt-5 pt-2">
            @include('components/homepage-articles-child')
        </div>
    </section>
    {{-- ---------------- End Daftar Berita ------------ --}}

    <footer>
        <div id="section_2" class="py-4 text-center">
            Copyright @ 2023. <a href="https://dinsos.jakarta.go.id/">Dinas Sosial DKI Jakarta</a>. All Right Reserved
        </div>
    </footer>
@endsection
