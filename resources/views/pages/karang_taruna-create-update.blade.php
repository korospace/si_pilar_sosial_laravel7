@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
    <link rel="stylesheet" href="{{ asset('css/plugins/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('js/plugins/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/pages/karang_taruna-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'karang_taruna.main'])

    <div class="content mt-4">
		<div class="container-fluid">

            <div class="row px-2 mb-5" style="display: {{ $karangTaruna != null ? '' : 'none' }}">
                <div class="col-12">
                    <div class="btn btn-outline-secondary">
                        status: &nbsp;
                        @if ($karangTaruna != null && $karangTaruna->status == "ditolak")
                        <span class="text-bold text-danger">
                            {{ $karangTaruna != null ? strtoupper($karangTaruna->status) : '' }}
                        </span>
                        @elseif ($karangTaruna != null && $karangTaruna->status == "diterima")
                        <span class="text-bold text-success">
                            {{ $karangTaruna != null ? strtoupper($karangTaruna->status) : '' }}
                        </span>
                        @else
                        <span class="text-bold">
                            {{ $karangTaruna != null ? strtoupper($karangTaruna->status) : '' }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row px-2 pb-5">
				<form id="formCreateUpdateKarangTaruna" class="col-12" autocomplete="off" style="position: relative;">
                    @if($karangTaruna != null)
                    <input type="text" id="id" name="id" value="{{ $karangTaruna->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif
                    <input type="text" id="level_id" name="level_id" value="{{ $user->level_id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    <input type="text" id="region_id" name="region_id" value="{{ $user->site ? $user->site->region_id : '' }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">

                    <div class="row mb-4">
                        @if($user->level_id == 1)
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site"><small><b>Wilayah</b></small></label>
                                <input type="text" id="site_id" name="site_id" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $karangTaruna != null ? $karangTaruna->site_id : '' }}">
                                <input type="text" class="form-control" id="site" name="site" value="{{ $karangTaruna != null ? $karangTaruna->site->name : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status"><small><b>Status</b></small></label>
                                <select id="status" name="status" class="custom-select select2bs4" value="{{ $karangTaruna != null ? $karangTaruna->status : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['diperiksa','diterima','ditolak'] as $status)
                                        <option value="{{ $status }}" {{ $karangTaruna != null && $status == $karangTaruna->status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                <span id="status-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year"><small><b>Data Tahun</b></small></label>
                                <select id="year" name="year" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        @if ($karangTaruna != null)
                                            <option value="{{ $year }}" {{ $year == $karangTaruna->year ? 'selected' : '' }}>{{ $year }}</option>
                                        @else
                                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                        @endif
                                    @endfor
                                </select>
                                <span id="year-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama"><small><b>Nama Lembaga</b></small></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $karangTaruna != null ? $karangTaruna->nama : '' }}">
                                <span id="nama-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama_ketua"><small><b>Ketua</b></small></label>
                                <input type="text" class="form-control" id="nama_ketua" name="nama_ketua" value="{{ $karangTaruna != null ? $karangTaruna->nama_ketua : '' }}">
                                <span id="nama_ketua-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telepon"><small><b>No. HP</b></small></label>
                                <input type="text" class="form-control" id="telepon" name="telepon" value="{{ $karangTaruna != null ? $karangTaruna->telepon : '' }}">
                                <span id="telepon-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="keaktifan_status"><small><b>Keaktifan</b></small></label>
                                <select id="keaktifan_status" name="keaktifan_status" class="custom-select select2bs4" value="{{ $karangTaruna != null ? $karangTaruna->keaktifan_status : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['tidak aktif','kurang aktif','aktif','sangat aktif'] as $keaktifan_status)
                                        <option value="{{ $keaktifan_status }}" {{ $karangTaruna != null && $keaktifan_status == $karangTaruna->keaktifan_status ? 'selected' : '' }}>{{ $keaktifan_status }}</option>
                                    @endforeach
                                </select>
                                <span id="keaktifan_status-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="program_unggulan"><small><b>Program unggulan</b></small></label>
                                <input type="text" class="form-control" id="program_unggulan" name="program_unggulan" value="{{ $karangTaruna != null ? $karangTaruna->program_unggulan : '' }}">
                                <span id="program_unggulan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">ALAMAT</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_jalan"><small><b>Jalan</b></small></label>
                                <input type="text" class="form-control" id="alamat_jalan" name="alamat_jalan" value="{{ $karangTaruna != null ? $karangTaruna->alamat_jalan : '' }}">
                                <span id="alamat_jalan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_rt"><small><b>RT</b></small></label>
                                <input type="text" class="form-control" id="alamat_rt" name="alamat_rt" value="{{ $karangTaruna != null ? $karangTaruna->alamat_rt : '' }}">
                                <span id="alamat_rt-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_rw"><small><b>RW</b></small></label>
                                <input type="text" class="form-control" id="alamat_rw" name="alamat_rw" value="{{ $karangTaruna != null ? $karangTaruna->alamat_rw : '' }}">
                                <span id="alamat_rw-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_kelurahan"><small><b>Kelurahan</b></small></label>
                                <input type="text" id="alamat_kelurahan_hide" name="alamat_kelurahan_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $karangTaruna != null ? $karangTaruna->alamat_kelurahan : '' }}">
                                <input type="text" class="form-control" id="alamat_kelurahan" name="alamat_kelurahan" value="{{ $karangTaruna != null ? $karangTaruna->alamat_kelurahan : '' }}">
                                <span id="alamat_kelurahan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_kecamatan"><small><b>Kecamatan</b></small></label>
                                <input type="text" id="alamat_kecamatan_hide" name="alamat_kecamatan_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $karangTaruna != null ? $karangTaruna->alamat_kecamatan : '' }}">
                                <input type="text" class="form-control" id="alamat_kecamatan" name="alamat_kecamatan" value="{{ $karangTaruna != null ? $karangTaruna->alamat_kecamatan : '' }}">
                                <span id="alamat_kecamatan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">KEPENGURUSAN</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kepengurusan_status"><small><b>Status</b></small></label>
                                <select id="kepengurusan_status" name="kepengurusan_status" class="custom-select select2bs4" value="{{ $karangTaruna != null ? $karangTaruna->kepengurusan_status : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['sudah terbentuk','belum terbentuk'] as $kepengurusan_status)
                                        <option value="{{ $kepengurusan_status }}" {{ $karangTaruna != null && $kepengurusan_status == $karangTaruna->kepengurusan_status ? 'selected' : '' }}>{{ $kepengurusan_status }}</option>
                                    @endforeach
                                </select>
                                <span id="kepengurusan_status-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-kepengurusan">
                            <div class="form-group">
                                <label for="kepengurusan_sk_tgl"><small><b>Tanggal sk</b></small></label>
                                <input type="text" class="form-control tgl" id="kepengurusan_sk_tgl" name="kepengurusan_sk_tgl" value="{{ $karangTaruna != null ? $karangTaruna->kepengurusan_sk_tgl : '' }}">
                                <span id="kepengurusan_sk_tgl-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-kepengurusan">
                            <div class="form-group">
                                <label for="kepengurusan_periode_tahun"><small><b>Periode Tahun</b></small></label>
                                <input type="text" class="form-control" id="kepengurusan_periode_tahun" name="kepengurusan_periode_tahun" value="{{ $karangTaruna != null ? $karangTaruna->kepengurusan_periode_tahun : '' }}">
                                <span id="kepengurusan_periode_tahun-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-kepengurusan">
                            <div class="form-group">
                                <label for="kepengurusan_jumlah"><small><b>Jumlah</b></small></label>
                                <input type="text" class="form-control" id="kepengurusan_jumlah" name="kepengurusan_jumlah" value="{{ $karangTaruna != null ? $karangTaruna->kepengurusan_jumlah : '' }}">
                                <span id="kepengurusan_jumlah-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-kepengurusan">
                            <div class="form-group">
                                <label for="kepengurusan_pejabat"><small><b>Pejabat</b></small></label>
                                <input type="text" class="form-control" id="kepengurusan_pejabat" name="kepengurusan_pejabat" value="{{ $karangTaruna != null ? $karangTaruna->kepengurusan_pejabat : '' }}">
                                <span id="kepengurusan_pejabat-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    @if ($karangTaruna == null || $user->level_id == 1)
                        <button type="button" class="w-100 mt-4 btn btn-success" onclick="saveData()">SIMPAN</button>
                    @elseif ($user->level_id == 2 && $karangTaruna != null && $karangTaruna->status == 'diperiksa')
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="w-100 mt-4 btn btn-danger" onclick="verifKarangTaruna(this, event, 'ditolak', {{ $karangTaruna->id }})">
                                    TOLAK
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="w-100 mt-4 btn btn-success" onclick="verifKarangTaruna(this, event, 'diterima', {{ $karangTaruna->id }})">
                                    TERIMA
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
