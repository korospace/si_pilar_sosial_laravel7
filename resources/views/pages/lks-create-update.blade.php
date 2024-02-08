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
    <script src="{{ asset('js/pages/lks-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'lks.main'])

    <div class="content mt-4">
		<div class="container-fluid">

            <div class="row px-2 mb-5" style="display: {{ $lks != null ? '' : 'none' }}">
                <div class="col-12">
                    <div class="btn btn-outline-secondary">
                        status: &nbsp;
                        @if ($lks != null && $lks->status == "ditolak")
                        <span class="text-bold text-danger">
                            {{ $lks != null ? strtoupper($lks->status) : '' }}
                        </span>
                        @elseif ($lks != null && $lks->status == "diterima")
                        <span class="text-bold text-success">
                            {{ $lks != null ? strtoupper($lks->status) : '' }}
                        </span>
                        @else
                        <span class="text-bold">
                            {{ $lks != null ? strtoupper($lks->status) : '' }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row px-2 pb-5">
				<form id="formCreateUpdateLks" class="col-12" autocomplete="off" style="position: relative;">
                    @if($lks != null)
                    <input type="text" id="id" name="id" value="{{ $lks->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif
                    <input type="text" id="level_id" name="level_id" value="{{ $user->level_id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    <input type="text" id="region_id" name="region_id" value="{{ $user->site ? $user->site->region_id : '' }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">

                    @if($user->level_id == 1)
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site"><small><b>Wilayah</b></small></label>
                                <input type="text" id="site_id" name="site_id" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $lks != null ? $lks->site_id : '' }}">
                                <input type="text" class="form-control" id="site" name="site" value="{{ $lks != null ? $lks->site->name : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year"><small><b>Data Tahun</b></small></label>
                                <select id="year" name="year" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        @if ($lks != null)
                                            <option value="{{ $year }}" {{ $year == $lks->year ? 'selected' : '' }}>{{ $year }}</option>
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
                                <label for="status"><small><b>Status</b></small></label>
                                <select id="status" name="status" class="custom-select select2bs4" value="{{ $lks != null ? $lks->status : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['diperiksa','diterima','ditolak'] as $status)
                                        <option value="{{ $status }}" {{ $lks != null && $status == $lks->status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                <span id="status-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama"><small><b>Nama</b></small></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $lks != null ? $lks->nama : '' }}">
                                <span id="nama-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama_ketua"><small><b>Ketua</b></small></label>
                                <input type="text" class="form-control" id="nama_ketua" name="nama_ketua" value="{{ $lks != null ? $lks->nama_ketua : '' }}">
                                <span id="nama_ketua-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_telp_yayasan"><small><b>Telepon</b></small></label>
                                <input type="text" class="form-control" id="no_telp_yayasan" name="no_telp_yayasan" value="{{ $lks != null ? $lks->no_telp_yayasan : '' }}">
                                <span id="no_telp_yayasan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="npwp"><small><b>NPWP</b></small></label>
                                <input type="text" class="form-control" id="npwp" name="npwp" value="{{ $lks != null ? $lks->npwp : '' }}">
                                <span id="npwp-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jenis_layanan"><small><b>Jenis Layanan</b></small></label>
                                <select id="jenis_layanan" name="jenis_layanan" class="custom-select select2bs4" value="{{ $lks != null ? $lks->jenis_layanan : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach ($LayananLks as $row)
                                        <option value="{{ $row->name }}" {{ $lks != null && $row->name == $lks->jenis_layanan ? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <span id="jenis_layanan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="akreditasi"><small><b>Akreditasi</b></small></label>
                                <select id="akreditasi" name="akreditasi" class="custom-select select2bs4" value="{{ $lks != null ? $lks->akreditasi : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach ($AkreditasiLks as $row)
                                        <option value="{{ $row->name }}" {{ $lks != null && $row->name == $lks->akreditasi ? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <span id="akreditasi-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="akreditasi_tgl"><small><b>Tanggal Akreditasi</b></small></label>
                                <input type="text" class="form-control tgl" id="akreditasi_tgl" name="akreditasi_tgl" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->akreditasi_tgl)) : '' }}">
                                <span id="akreditasi_tgl-error" class="invalid-feedback"></span>
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
                                <input type="text" class="form-control" id="alamat_jalan" name="alamat_jalan" value="{{ $lks != null ? $lks->alamat_jalan : '' }}">
                                <span id="alamat_jalan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_rt"><small><b>RT</b></small></label>
                                <input type="text" class="form-control" id="alamat_rt" name="alamat_rt" value="{{ $lks != null ? $lks->alamat_rt : '' }}">
                                <span id="alamat_rt-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_rw"><small><b>RW</b></small></label>
                                <input type="text" class="form-control" id="alamat_rw" name="alamat_rw" value="{{ $lks != null ? $lks->alamat_rw : '' }}">
                                <span id="alamat_rw-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_kelurahan"><small><b>Kelurahan</b></small></label>
                                <input type="text" id="alamat_kelurahan_hide" name="alamat_kelurahan_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $lks != null ? $lks->alamat_kelurahan : '' }}">
                                <input type="text" class="form-control" id="alamat_kelurahan" name="alamat_kelurahan" value="{{ $lks != null ? $lks->alamat_kelurahan : '' }}">
                                <span id="alamat_kelurahan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_kecamatan"><small><b>Kecamatan</b></small></label>
                                <input type="text" id="alamat_kecamatan_hide" name="alamat_kecamatan_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $lks != null ? $lks->alamat_kecamatan : '' }}">
                                <input type="text" class="form-control" id="alamat_kecamatan" name="alamat_kecamatan" value="{{ $lks != null ? $lks->alamat_kecamatan : '' }}">
                                <span id="alamat_kecamatan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">PENDIRIAN</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="akta_pendirian_nomor"><small><b>Nomor AKTA</b></small></label>
                                <input type="text" class="form-control" id="akta_pendirian_nomor" name="akta_pendirian_nomor" value="{{ $lks != null ? $lks->akta_pendirian_nomor : '' }}">
                                <span id="akta_pendirian_nomor-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="akta_pendirian_tgl"><small><b>Tanggal AKTA</b></small></label>
                                <input type="text" class="form-control tgl" id="akta_pendirian_tgl" name="akta_pendirian_tgl" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->akta_pendirian_tgl)) : '' }}">
                                <span id="akta_pendirian_tgl-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sk_hukumham_pendirian_nomor"><small><b>No. sk Hukum HAM</b></small></label>
                                <input type="text" class="form-control" id="sk_hukumham_pendirian_nomor" name="sk_hukumham_pendirian_nomor" value="{{ $lks != null ? $lks->sk_hukumham_pendirian_nomor : '' }}">
                                <span id="sk_hukumham_pendirian_nomor-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sk_hukumham_pendirian_tgl"><small><b>Tanggal sk</b></small></label>
                                <input type="text" class="form-control tgl" id="sk_hukumham_pendirian_tgl" name="sk_hukumham_pendirian_tgl" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->sk_hukumham_pendirian_tgl)) : '' }}">
                                <span id="sk_hukumham_pendirian_tgl-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">PERUBAHAN</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="akta_perubahan_nomor"><small><b>Nomor AKTA</b></small></label>
                                <input type="text" class="form-control" id="akta_perubahan_nomor" name="akta_perubahan_nomor" value="{{ $lks != null ? $lks->akta_perubahan_nomor : '' }}">
                                <span id="akta_perubahan_nomor-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="akta_perubahan_tgl"><small><b>Tanggal AKTA</b></small></label>
                                <input type="text" class="form-control tgl" id="akta_perubahan_tgl" name="akta_perubahan_tgl" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->akta_perubahan_tgl)) : '' }}">
                                <span id="akta_perubahan_tgl-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sk_hukumham_perubahan_nomor"><small><b>No. sk Hukum HAM</b></small></label>
                                <input type="text" class="form-control" id="sk_hukumham_perubahan_nomor" name="sk_hukumham_perubahan_nomor" value="{{ $lks != null ? $lks->sk_hukumham_perubahan_nomor : '' }}">
                                <span id="sk_hukumham_perubahan_nomor-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sk_hukumham_perubahan_tgl"><small><b>Tanggal sk</b></small></label>
                                <input type="text" class="form-control tgl" id="sk_hukumham_perubahan_tgl" name="sk_hukumham_perubahan_tgl" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->sk_hukumham_perubahan_tgl)) : '' }}">
                                <span id="sk_hukumham_perubahan_tgl-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">SK DOMISILI YAYASAN</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sk_domisili_yayasan_nomor"><small><b>Nomor</b></small></label>
                                <input type="text" class="form-control" id="sk_domisili_yayasan_nomor" name="sk_domisili_yayasan_nomor" value="{{ $lks != null ? $lks->sk_domisili_yayasan_nomor : '' }}">
                                <span id="sk_domisili_yayasan_nomor-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sk_domisili_yayasan_tgl_terbit"><small><b>Tanggal</b></small></label>
                                <input type="text" class="form-control tgl" id="sk_domisili_yayasan_tgl_terbit" name="sk_domisili_yayasan_tgl_terbit" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->sk_domisili_yayasan_tgl_terbit)) : '' }}">
                                <span id="sk_domisili_yayasan_tgl_terbit-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sk_domisili_yayasan_masa_berlaku"><small><b>Masa Berlaku</b></small></label>
                                <input type="text" class="form-control" id="sk_domisili_yayasan_masa_berlaku" name="sk_domisili_yayasan_masa_berlaku" value="{{ $lks != null ? $lks->sk_domisili_yayasan_masa_berlaku : '' }}">
                                <span id="sk_domisili_yayasan_masa_berlaku-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">TANDA DAFTAR YAYASAN</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanda_daftar_yayasan_nomor"><small><b>Nomor</b></small></label>
                                <input type="text" class="form-control" id="tanda_daftar_yayasan_nomor" name="tanda_daftar_yayasan_nomor" value="{{ $lks != null ? $lks->tanda_daftar_yayasan_nomor : '' }}">
                                <span id="tanda_daftar_yayasan_nomor-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanda_daftar_yayasan_tgl_terbit"><small><b>Tanggal</b></small></label>
                                <input type="text" class="form-control tgl" id="tanda_daftar_yayasan_tgl_terbit" name="tanda_daftar_yayasan_tgl_terbit" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->tanda_daftar_yayasan_tgl_terbit)) : '' }}">
                                <span id="tanda_daftar_yayasan_tgl_terbit-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanda_daftar_yayasan_masa_berlaku"><small><b>Masa Berlaku</b></small></label>
                                <input type="text" class="form-control" id="tanda_daftar_yayasan_masa_berlaku" name="tanda_daftar_yayasan_masa_berlaku" value="{{ $lks != null ? $lks->tanda_daftar_yayasan_masa_berlaku : '' }}">
                                <span id="tanda_daftar_yayasan_masa_berlaku-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">IZIN KEGIATAN YAYASAN</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="izin_kegiatan_yayasan_nomor"><small><b>Nomor</b></small></label>
                                <input type="text" class="form-control" id="izin_kegiatan_yayasan_nomor" name="izin_kegiatan_yayasan_nomor" value="{{ $lks != null ? $lks->izin_kegiatan_yayasan_nomor : '' }}">
                                <span id="izin_kegiatan_yayasan_nomor-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="izin_kegiatan_yayasan_tgl_terbit"><small><b>Tanggal</b></small></label>
                                <input type="text" class="form-control tgl" id="izin_kegiatan_yayasan_tgl_terbit" name="izin_kegiatan_yayasan_tgl_terbit" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->izin_kegiatan_yayasan_tgl_terbit)) : '' }}">
                                <span id="izin_kegiatan_yayasan_tgl_terbit-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="izin_kegiatan_yayasan_masa_berlaku"><small><b>Masa Berlaku</b></small></label>
                                <input type="text" class="form-control" id="izin_kegiatan_yayasan_masa_berlaku" name="izin_kegiatan_yayasan_masa_berlaku" value="{{ $lks != null ? $lks->izin_kegiatan_yayasan_masa_berlaku : '' }}">
                                <span id="izin_kegiatan_yayasan_masa_berlaku-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">NO. INDUK BERUSAHA</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="induk_berusaha_nomor"><small><b>Nomor</b></small></label>
                                <input type="text" class="form-control" id="induk_berusaha_nomor" name="induk_berusaha_nomor" value="{{ $lks != null ? $lks->induk_berusaha_nomor : '' }}">
                                <span id="induk_berusaha_nomor-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="induk_berusaha_tgl"><small><b>Tanggal</b></small></label>
                                <input type="text" class="form-control tgl" id="induk_berusaha_tgl" name="induk_berusaha_tgl" value="{{ $lks != null ? strftime('%d %B %Y', strtotime($lks->induk_berusaha_tgl)) : '' }}">
                                <span id="induk_berusaha_tgl-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    @if ($lks == null || $user->level_id == 1)
                        <button type="button" class="w-100 mt-4 btn btn-success" onclick="saveData()">SIMPAN</button>
                    @elseif ($user->level_id == 2 && $lks != null && $lks->status == 'diperiksa')
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="w-100 mt-4 btn btn-danger" onclick="verifLKS(this, event, 'ditolak', {{ $lks->id }})">
                                    TOLAK
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="w-100 mt-4 btn btn-success" onclick="verifLKS(this, event, 'diterima', {{ $lks->id }})">
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
