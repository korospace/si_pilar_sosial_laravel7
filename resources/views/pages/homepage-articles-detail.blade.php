@extends('layouts.main')

@push('meta')
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/toastify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/homepage-articles-detail.css') }}">
@endpush

@push('jscript')
    <script>
        const SLUG = '{{ $article->slug }}';
    </script>

    <script src="{{ asset('js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('js/plugins/showToastify.js') }}"></script>
    <script src="{{ asset('js/pages/homepage-articles-detail.js') }}"></script>
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

    {{-- ---------------- Detail Berita ---------------- --}}
    <section class="blog-single section position-relative" style="padding-top: 0;z-index: 0;">
        <div class="container px-4 px-sm-5">
            <div class="row position-relative">

                <div id="title_wraper" class="col-12 mt-5">
                    <h2 id="title" class="">
                        Berita
                        <a href="{{ route('homepage.articles') }}">Kembali »</a>
                    </h2>
                </div>

                <div class="col-lg-8 col-12 main-content">
                    <div class="blog-single-main">
                        <div class="row">
                            <div class="col-12">
                                <div class="image position-relative">
                                    <img src="{{ asset('images/default-thumbnail.webp') }}" alt="#" class="w-100" style="opacity: 0;">
                                    <img src="{{ $article->public_thumbnail }}" alt="" id="blog-img" class="w-100 h-100 position-absolute img-thumbnail" style="z-index: 10;left:0;">
                                </div>
                                <div class="blog-detail">
                                    <h1 id="blog-title" class="blog-title text-justify" style="font-family: serif;font-weight: 400;">{{ $article->title }}</h1>
                                    <div class="blog-meta" style="">
                                        <h6 id="blog-date" class="author skeleton mt-2"></h6>
                                    </div>
                                    <div id="blog-content" class="text-justify" style="font-family: 'qc-medium';">
                                    <?php for ($i=0; $i < 4; $i++) { ?>
                                        <div class="row mb-5 px-3">
                                            <div class="col-12 mb-3 skeleton"></div>
                                            <div class="col-12 mb-3 skeleton"></div>
                                            <div class="col-12 mb-3 skeleton"></div>
                                            <div class="col-6 skeleton"></div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                                <div id="blog-share" class="mt-4 w-100 d-none">
                                    <span class="share-label">Share this <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z"/></svg></span>
                                    <a class="share-link share-whatsapp" rel="nofollow" target="_blank" href="https://api.whatsapp.com/send?text=Bank%20Sampah%20Budi%20Luhur%20Raih%20Juara%20Umum%20dan%20Rekor%20Indonesia%20Award%20memilah%20Sampah%20https%3A%2F%2Fwww.budiluhur.ac.id%2Fbank-sampah-budi-luhur-raih-juara-umum-dan-rekor-indonesia-award-memilah-sampah%2F"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg> WhatsApp</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 sidebar-content pt-4">
                    <div class="main-sidebar mt-1">
                        <div class="single-widget recent-post pb-5">
                            <h3 class="title" style="font-family: 'qc-semibold';opacity: 0.8;">Berita Lainnya</h3>
                            <!-- Single Post -->
                            <div id="blog-recommended" class="row" style="font-family: 'qc-medium';">
                                <?php for ($i=0; $i < 4; $i++) { ?>
                                    <a id="single-post" class="col-12 col-sm-6 col-lg-12 mb-4" href="">
                                        <div class="image position-relative skeleton">
                                            <img src="{{ asset('images/default-thumbnail.webp') }}" alt="" class="w-100" style="opacity: 0;">
                                        </div>
                                        <div class="content mt-2">
                                            <h5 id="title" class="text-muted skeleton">

                                            </h5>
                                            <p id="date" class="mt-2 skeleton"></p>
                                        </div>
                                    </a>
                                    <hr width="">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- ---------------- End Detail Berita ------------ --}}

    <footer>
        <div id="section_2" class="py-4 px-3 text-center">
            Copyright @ 2023. <a href="https://dinsos.jakarta.go.id/">Dinas Sosial DKI Jakarta</a>. All Right Reserved
        </div>
    </footer>
@endsection
