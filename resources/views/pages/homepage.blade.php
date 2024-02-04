@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/homepage.css') }}">
@endpush

@push('jscript')
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('js/pages/homepage.js') }}"></script>
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

    {{-- ---------------- Carousel ---------------------- --}}
    <section id="carousel_container">
        <div id="carousel_bg" class="d-flex justify-content-center align-items-center">
            <h1 class="">sistem informasi</h1>
            <h1 class="">pilar-pilar sosial</h1>
            <h1 class="">"Si Pilar" adalah platform informasi pilar-pilar sosial yang sangat penting dalam masyarakat. Pilar-pilar ini melibatkan entitas seperti Karang Taruna, Lembaga Kesejahteraan Sosial (LKS), Tenaga Kesejahteraan Sosial Kecamatan (TKSK), dan Pekerja Sosial Masyarakat (PSM).</h1>
        </div>
        <div class="carousel slide position-absolute" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item item active">
                    <div class="fill" style="background-image:url('{{ asset('images/banner1.jpeg') }}');"></div>
                </div>
                <div class="carousel-item item">
                    <div class="fill" style="background-image:url('{{ asset('images/banner1.jpeg') }}');"></div>
                </div>
            </div>
        </div>
    </section>
    {{-- ---------------- End Carousel ------------------ --}}

    {{-- ---------------- Pilar-pilar ------------------- --}}
    <section id="pilar_container" class="d-flex justify-content-center">
        <div class="row container">
            <div class="col-md-6 col-xl-3 mb-4 mb-xl-0">
                <div class="card py-4 px-4 h-100 shadow">
                    <div class="logo_wraper d-flex justify-content-center">
                        <img src="{{ asset('images/logo-psm.jpeg') }}" alt="" class="logo_pilar">
                    </div>
                    <div class="title_wraper text-center">
                        <h1>Pekarja Sosial Masyarakat</h1>
                    </div>
                    <div class="desc_wraper text-justify">
                        <p>Pekerjaan sosial masyarakat adalah warga masyarakat yang atas dasar rasa kesadaran dan tanggung jawab sosial serta di dorong oleh rasa kebersamaan, kekeluargaan dan kesetiakawanan sosial secara sukarela mengabdi di bidang kesejahteraan sosial</p>
                    </div>
                    <hr class="overlay">
                    <div id="psm" class="counter_wraper">

                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4 mb-xl-0">
                <div class="card py-4 px-4 h-100 shadow">
                    <div class="logo_wraper d-flex justify-content-center">
                        <img src="{{ asset('images/logo-lks.png') }}" alt="" class="logo_pilar">
                    </div>
                    <div class="title_wraper text-center">
                        <h1>Lembaga Kesejahteraan Sosial</h1>
                    </div>
                    <div class="desc_wraper text-justify">
                        <p>Lembaga Kesejahteraan Sosial adalah organisasi sosial atau perkumpulan sosial yang melaksanakan penyelenggaraan Kesejahteraan Sosial yang dibentuk oleh masyarakat, baik yang berbadan hukum maupun yang tidak berbadan hukum</p>
                    </div>
                    <hr class="overlay">
                    <div id="lks" class="counter_wraper">

                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4 mb-xl-0">
                <div class="card py-4 px-4 h-100 shadow">
                    <div class="logo_wraper d-flex justify-content-center">
                        <img src="{{ asset('images/logo-tksk.png') }}" alt="" class="logo_pilar">
                    </div>
                    <div class="title_wraper text-center">
                        <h1>Tenaga Kesejahteraan Sosial Kecamatan</h1>
                    </div>
                    <div class="desc_wraper text-justify">
                        <p>Tenaga Kesejahteraan Sosial Kecamatan adalah seseorang yang diberi tugas, fungsi, dan kewenangan oleh Kementerian Sosial, dinas sosial daerah provinsi, dan/atau dinas sosial daerah kabupaten/kota untuk membantu penyelenggaraan kesejahteraan sosial sesuai lingkup wilayah penugasan</p>
                    </div>
                    <hr class="overlay">
                    <div id="tksk" class="counter_wraper">

                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4 mb-xl-0">
                <div class="card py-4 px-4 h-100 shadow">
                    <div class="logo_wraper d-flex justify-content-center">
                        <img src="{{ asset('images/logo-karangtaruna.jpg') }}" alt="" class="logo_pilar">
                    </div>
                    <div class="title_wraper text-center">
                        <h1>Karang Taruna</h1>
                    </div>
                    <div class="desc_wraper text-justify">
                        <p>Karang Taruna adalah organisasi yang dibentuk oleh masyarakat sebagai wadah generasi muda untuk mengembangkan diri, tumbuh, dan berkembang atas dasar kesadaran serta tanggung jawab sosial dari, oleh, dan untuk generasi muda, yang berorientasi pada tercapainya kesejahteraan sosial bagi masyarakat</p>
                    </div>
                    <hr class="overlay">
                    <div id="karang_taruna" class="counter_wraper">

                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- ---------------- End Pilar-pilar --------------- --}}

    {{-- ---------------- Berita terbatu ---------------- --}}
    <section id="berita_terbaru" class="d-flex justify-content-center">
        <div class="row container">
            <div class="col-12">
                <h2 id="title" class="">
                    Berita Terbaru

                    <a href="{{ route('homepage.articles') }}">Selengkapnya »</a>
                </h2>
            </div>

            <div class="col-12 mt-4">
                <div id="card_wraper" class="row">

                </div>
            </div>
        </div>
    </section>
    {{-- ---------------- End Berita terbatu ------------ --}}

    <footer>
        <div id="section_2" class="py-4 px-3 text-center">
            Copyright @ 2023. <a href="https://dinsos.jakarta.go.id/">Dinas Sosial DKI Jakarta</a>. All Right Reserved
        </div>
    </footer>
@endsection
