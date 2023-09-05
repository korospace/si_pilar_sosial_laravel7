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
    <script src="{{ asset('js/pages/psm-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'psm.main'])

    <div class="content mt-4">
		<div class="container-fluid">

            <div class="row px-2 mb-5" style="display: {{ $psm != null ? '' : 'none' }}">
                <div class="col-12">
                    <div class="btn btn-outline-secondary">
                        status: &nbsp;
                        @if ($psm != null && $psm->status == "ditolak")
                        <span class="text-bold text-danger">
                            {{ $psm != null ? strtoupper($psm->status) : '' }}
                        </span>
                        @elseif ($psm != null && $psm->status == "diterima")
                        <span class="text-bold text-success">
                            {{ $psm != null ? strtoupper($psm->status) : '' }}
                        </span>
                        @else
                        <span class="text-bold">
                            {{ $psm != null ? strtoupper($psm->status) : '' }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row px-2 pb-5">
				<form id="formCreateUpdatePsm" class="col-12" autocomplete="off" style="position: relative;">
                    @if($psm != null)
                    <input type="text" id="id" name="id" value="{{ $psm->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif
                    <input type="text" id="level_id" name="level_id" value="{{ $user->level_id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    <input type="text" id="region_id" name="region_id" value="{{ $user->site ? $user->site->region_id : '' }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">

                    @if($user->level_id == 1)
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site"><small><b>Site</b></small></label>
                                <input type="text" id="site_id" name="site_id" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $psm != null ? $psm->site_id : '' }}">
                                <input type="text" class="form-control" id="site" name="site" value="{{ $psm != null ? $psm->site->name : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status"><small><b>Status</b></small></label>
                                <select id="status" name="status" class="custom-select select2bs4" value="{{ $psm != null ? $psm->status : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['diperiksa','diterima','ditolak'] as $status)
                                        <option value="{{ $status }}" {{ $psm != null && $status == $psm->status ? 'selected' : '' }}>{{ $status }}</option>
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
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $psm != null ? $psm->nama : '' }}">
                                <span id="nama-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nik"><small><b>NIK</b></small></label>
                                <input type="text" class="form-control" id="nik" name="nik" value="{{ $psm != null ? $psm->nik : '' }}">
                                <span id="nik-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telepon"><small><b>Telepon</b></small></label>
                                <input type="text" class="form-control" id="telepon" name="telepon" value="{{ $psm != null ? $psm->telepon : '' }}">
                                <span id="telepon-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pendidikan_terakhir"><small><b>Pendidikan</b></small></label>
                                <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="custom-select select2bs4" value="{{ $psm != null ? $psm->pendidikan_terakhir : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach ($educations as $education)
                                        <option value="{{ $education->name }}" {{ $psm != null && $education->name == $psm->pendidikan_terakhir ? 'selected' : '' }}>{{ $education->name }}</option>
                                    @endforeach
                                </select>
                                <span id="pendidikan_terakhir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tempat_lahir"><small><b>Tempat Lahir</b></small></label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ $psm != null ? $psm->tempat_lahir : '' }}">
                                <span id="tempat_lahir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanggal_lahir"><small><b>Tanggal Lahir</b></small></label>
                                <input type="text" class="form-control tgl" id="tanggal_lahir" name="tanggal_lahir" value="{{ $psm != null ? $psm->tanggal_lahir : '' }}">
                                <span id="tanggal_lahir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tingkatan_diklat"><small><b>Tingkatan Diklat</b></small></label>
                                <select id="tingkatan_diklat" name="tingkatan_diklat" class="custom-select select2bs4" value="{{ $psm != null ? $psm->tingkatan_diklat : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['belum pernah', 'dasar', 'lanjutan', 'pengembangan', 'khusus'] as $tingkatan_diklat)
                                        <option value="{{ $tingkatan_diklat }}" {{ $psm != null && $tingkatan_diklat == $psm->tingkatan_diklat ? 'selected' : '' }}>{{ $tingkatan_diklat }}</option>
                                    @endforeach
                                </select>
                                <span id="tingkatan_diklat-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kondisi_existing"><small><b>Kondisi Existing</b></small></label>
                                <select id="kondisi_existing" name="kondisi_existing" class="custom-select select2bs4" value="{{ $psm != null ? $psm->kondisi_existing : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['tidak aktif', 'kurang aktif', 'aktif', 'sangat aktif'] as $kondisi_existing)
                                        <option value="{{ $kondisi_existing }}" {{ $psm != null && $kondisi_existing == $psm->kondisi_existing ? 'selected' : '' }}>{{ $kondisi_existing }}</option>
                                    @endforeach
                                </select>
                                <span id="kondisi_existing-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jenis_kelamin"><small><b>Jenis Kelamin</b></small></label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="custom-select select2bs4" value="{{ $psm != null ? $psm->jenis_kelamin : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['L' => 'laki-laki', 'P' => 'perempuan'] as $key => $kelamin)
                                        <option value="{{ $key }}" {{ $psm != null && $key == $psm->jenis_kelamin ? 'selected' : '' }}>{{ $kelamin }}</option>
                                    @endforeach
                                </select>
                                <span id="jenis_kelamin-error" class="invalid-feedback"></span>
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

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_jalan"><small><b>Jalan</b></small></label>
                                <input type="text" class="form-control" id="alamat_jalan" name="alamat_jalan" value="{{ $psm != null ? $psm->alamat_jalan : '' }}">
                                <span id="alamat_jalan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_rt"><small><b>RT</b></small></label>
                                <input type="text" class="form-control" id="alamat_rt" name="alamat_rt" value="{{ $psm != null ? $psm->alamat_rt : '' }}">
                                <span id="alamat_rt-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_rw"><small><b>RW</b></small></label>
                                <input type="text" class="form-control" id="alamat_rw" name="alamat_rw" value="{{ $psm != null ? $psm->alamat_rw : '' }}">
                                <span id="alamat_rw-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">TEMPAT TUGAS</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tempat_tugas_kecamatan"><small><b>Kecamatan</b></small></label>
                                <input type="text" id="tempat_tugas_kecamatan_hide" name="tempat_tugas_kecamatan_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $psm != null ? $psm->tempat_tugas : '' }}">
                                <input type="text" class="form-control" id="tempat_tugas_kecamatan" name="tempat_tugas_kecamatan" value="{{ $psm != null ? $psm->tempat_tugas_kecamatan : '' }}">
                                <span id="tempat_tugas_kecamatan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tempat_tugas_kelurahan"><small><b>Kelurahan</b></small></label>
                                <input type="text" id="tempat_tugas_kelurahan_hide" name="tempat_tugas_kelurahan_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $psm != null ? $psm->tempat_tugas : '' }}">
                                <input type="text" class="form-control" id="tempat_tugas_kelurahan" name="tempat_tugas_kelurahan" value="{{ $psm != null ? $psm->tempat_tugas_kelurahan : '' }}">
                                <span id="tempat_tugas_kelurahan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">SERTIFIKASI</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sertifikasi_status"><small><b>Status</b></small></label>
                                <select id="sertifikasi_status" name="sertifikasi_status" class="custom-select select2bs4" value="{{ $psm != null ? $psm->sertifikasi_status : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['sudah','belum'] as $sertifikasi_status)
                                        <option value="{{ $sertifikasi_status }}" {{ $psm != null && $sertifikasi_status == $psm->sertifikasi_status ? 'selected' : '' }}>{{ $sertifikasi_status }}</option>
                                    @endforeach
                                </select>
                                <span id="sertifikasi_status-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sertifikasi">
                            <div class="form-group">
                                <label for="sertifikasi_tahun"><small><b>Tahun</b></small></label>
                                <select id="sertifikasi_tahun" name="sertifikasi_tahun" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @for ($year = 2000; $year <= date('Y'); $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <span id="sertifikasi_tahun-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    @if ($psm == null || $user->level_id == 1)
                        <button type="button" class="w-100 mt-4 btn btn-success" onclick="saveData()">SIMPAN</button>
                    @elseif ($user->level_id == 2 && $psm != null && $psm->status == 'diperiksa')
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="w-100 mt-4 btn btn-danger" onclick="verifPSM(this, event, 'ditolak', {{ $psm->id }})">
                                    TOLAK
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="w-100 mt-4 btn btn-success" onclick="verifPSM(this, event, 'diterima', {{ $psm->id }})">
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
