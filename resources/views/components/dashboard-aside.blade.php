<style>
    /* .sidebar-dark-custom {
        background-color: #092457;
    }

    .sidebar-dark-custom .nav-sidebar .nav-link.active {
        background-color: #27419C;
    }

    .sidebar-dark-custom .nav-sidebar .nav-link.active:hover {
        background-color: #3651b0;
    } */

    .sidebar-collapse a.brand-link{
        position: relative;
    }
    .sidebar-collapse span.brand-text{
        display: none;
    }
    .sidebar-collapse:hover span.brand-text{
        display: default;
    }
</style>

<script>
    function logout(el,event)
    {
        event.preventDefault();

        Swal.fire({
            title: `Apakah anda yakin keluar?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6E7881',
            confirmButtonText: 'Iya',
            cancelButtonText: 'tutup',
        }).then((result) => {
            if (result.isConfirmed) {
                showLoadingSpinner();
                window.location.replace(`${BASE_URL}/logout`);
            }
        })
    }
</script>

<aside class="main-sidebar sidebar-dark-secondary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link text-center d-flex align-items-center" style="flex-direction: column">
        <img src="{{ asset('images/logo-dinassosial-jakarta.webp') }}" alt="logo dinas sosial dki jakarta" class="img-circle elevation-3" style="opacity: .8; width: 70%">
        <span class="brand-text font-weight-bold mt-4">
            SISTEM INFORMASI <br>
            PILAR-PILAR SOSIAL
        </span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image d-flex align-items-center">
                <img src="{{ asset('images/default-profile.webp') }}" class="img-circle bg-secondary elevation-2" alt="User Image">
            </div>
            <div class="info d-flex ml-1" style="flex-direction: column;">
                <span class="text-white text-bold" style="font-size: 14px;">
                    Hai, {{ ucfirst(explode(" ", $user->name)[0]) }}
                </span>
                <small class="text-white" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;font-size: 12px;">
                    {{ $user->site ? $user->site->name : 'superadmin' }}
                </small>
            </div>
        </div>

        <nav class="user-panel pb-2 pt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard.main') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-home"></i>
                        <p>Beranda</p>
                    </a>
                </li>
                <li class="nav-item mb-2" style="display: {{ $user->level_id == 1 ? 'default' : 'none' }};">
                    <a href="{{ route('crudarticle.main') }}" class="nav-link {{ request()->is('crudarticle*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-file-alt"></i>
                        <p>Berita</p>
                    </a>
                </li>
                <li class="nav-item mb-2 {{ request()->is('site*') || request()->is('user*') || request()->is('education*') || request()->is('bank*') || request()->is('layanan_lks*') || request()->is('akreditasi_lks*') ? 'menu-open' : '' }}" style="display: {{ $user->level_id == 1 ? 'default' : 'none' }};">
                    <a href="#" class="nav-link mb-2">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Master
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item mb-2" style="font-size: 0.8em;">
                            <a href="{{ route('education.main') }}" class="nav-link {{ request()->is('education*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Pendidikan</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2" style="font-size: 0.8em;">
                            <a href="{{ route('bank.main') }}" class="nav-link {{ request()->is('bank*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Bank</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2" style="font-size: 0.8em;">
                            <a href="{{ route('layanan_lks.main') }}" class="nav-link {{ request()->is('layanan_lks*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Layanan LKS</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2" style="font-size: 0.8em;">
                            <a href="{{ route('akreditasi_lks.main') }}" class="nav-link {{ request()->is('akreditasi_lks*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Akreditasi LKS</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2" style="font-size: 0.8em;">
                            <a href="{{ route('site.main') }}" class="nav-link {{ request()->is('site*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Site</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2" style="font-size: 0.8em;">
                            <a href="{{ route('user.main') }}" class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>User</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('tksk.main') }}" class="nav-link {{ request()->is('tksk*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-gift"></i>
                        <p>TKSK</p>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('psm.main') }}" class="nav-link {{ request()->is('psm*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-people-carry"></i>
                        <p>PSM</p>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('lks.main') }}" class="nav-link {{ request()->is('lks*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-hands-helping"></i>
                        <p>LKS</p>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('karang_taruna.main') }}" class="nav-link {{ request()->is('karang_taruna*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-child"></i>
                        <p>Karang Taruna</p>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="mt-3 pb-3 mb-3 d-flex">
            <a href="" class="w-100 text-center py-3 btn btn-danger elevation-3" onclick="logout(this,event)">
                <i class="fa fa-power-off" style="font-size: 1.5em;"></i>
            </a>
        </div>
    </div>
</aside>
