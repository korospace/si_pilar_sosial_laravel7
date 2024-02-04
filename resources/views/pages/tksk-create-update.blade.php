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
    <script src="{{ asset('js/pages/tksk-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'tksk.main'])

    <div class="content mt-4">
		<div class="container-fluid">

            <div class="row px-2 mb-5" style="display: {{ $tksk != null ? '' : 'none' }}">
                <div class="col-12">
                    <div class="btn btn-outline-secondary">
                        status: &nbsp;
                        @if ($tksk != null && $tksk->status == "ditolak")
                        <span class="text-bold text-danger">
                            {{ $tksk != null ? strtoupper($tksk->status) : '' }}
                        </span>
                        @elseif ($tksk != null && $tksk->status == "diterima")
                        <span class="text-bold text-success">
                            {{ $tksk != null ? strtoupper($tksk->status) : '' }}
                        </span>
                        @else
                        <span class="text-bold">
                            {{ $tksk != null ? strtoupper($tksk->status) : '' }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row px-2 pb-5">
				<form id="formCreateUpdateTksk" class="col-12" autocomplete="off" style="position: relative;">
                    @if($tksk != null)
                    <input type="text" id="id" name="id" value="{{ $tksk->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif
                    <input type="text" id="level_id" name="level_id" value="{{ $user->level_id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    <input type="text" id="region_id" name="region_id" value="{{ $user->site ? $user->site->region_id : '' }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">

                    @if($user->level_id == 1)
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site"><small><b>Site</b></small></label>
                                <input type="text" id="site_id" name="site_id" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $tksk != null ? $tksk->site_id : '' }}">
                                <input type="text" class="form-control" id="site" name="site" value="{{ $tksk != null ? $tksk->site->name : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year"><small><b>Data Tahun</b></small></label>
                                <select id="year" name="year" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        @if ($tksk != null)
                                            <option value="{{ $year }}" {{ $year == $tksk->year ? 'selected' : '' }}>{{ $year }}</option>
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
                                <select id="status" name="status" class="custom-select select2bs4" value="{{ $tksk != null ? $tksk->status : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['diperiksa','diterima','ditolak'] as $status)
                                        <option value="{{ $status }}" {{ $tksk != null && $status == $tksk->status ? 'selected' : '' }}>{{ $status }}</option>
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
                                <label for="no_induk_anggota"><small><b>No. Induk Anggota</b></small></label>
                                <input type="text" class="form-control" id="no_induk_anggota" name="no_induk_anggota" value="{{ $tksk != null ? $tksk->no_induk_anggota : '' }}">
                                <span id="no_induk_anggota-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_kartu_registrasi"><small><b>No. Kartu Registrasi</b></small></label>
                                <input type="text" class="form-control" id="no_kartu_registrasi" name="no_kartu_registrasi" value="{{ $tksk != null ? $tksk->no_kartu_registrasi : '' }}">
                                <span id="no_kartu_registrasi-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tempat_tugas"><small><b>Tempat Tugas</b> <i>(kecamatan)</i></small></label>
                                <input type="text" id="tempat_tugas_hide" name="tempat_tugas_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $tksk != null ? $tksk->tempat_tugas : '' }}">
                                <input type="text" class="form-control" id="tempat_tugas" name="tempat_tugas" value="{{ $tksk != null ? $tksk->tempat_tugas : '' }}">
                                <span id="tempat_tugas-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama"><small><b>Nama</b></small></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $tksk != null ? $tksk->nama : '' }}">
                                <span id="nama-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama_ibu_kandung"><small><b>Nama Ibu Kandung</b></small></label>
                                <input type="text" class="form-control" id="nama_ibu_kandung" name="nama_ibu_kandung" value="{{ $tksk != null ? $tksk->nama_ibu_kandung : '' }}">
                                <span id="nama_ibu_kandung-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nik"><small><b>NIK</b></small></label>
                                <input type="text" class="form-control" id="nik" name="nik" value="{{ $tksk != null ? $tksk->nik : '' }}">
                                <span id="nik-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telepon"><small><b>No. telp</b></small></label>
                                <input type="text" class="form-control" id="telepon" name="telepon" value="{{ $tksk != null ? $tksk->telepon : '' }}">
                                <span id="telepon-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tempat_lahir"><small><b>Tempat Lahir</b></small></label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ $tksk != null ? $tksk->tempat_lahir : '' }}">
                                <span id="tempat_lahir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanggal_lahir"><small><b>Tanggal Lahir</b></small></label>
                                <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ $tksk != null ? $tksk->tanggal_lahir : '' }}">
                                <span id="tanggal_lahir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><small><b>Usia</b> <i>(tahun)</i></small></label>
                                <input type="text" class="form-control" id="usia" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pendidikan_terakhir"><small><b>Pendidikan Terakhir</b></small></label>
                                <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="custom-select select2bs4" value="{{ $tksk != null ? $tksk->pendidikan_terakhir : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach ($educations as $education)
                                        <option value="{{ $education->name }}" {{ $tksk != null && $education->name == $tksk->pendidikan_terakhir ? 'selected' : '' }}>{{ $education->name }}</option>
                                    @endforeach
                                </select>
                                <span id="pendidikan_terakhir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jenis_kelamin"><small><b>Jenis Kelamin</b></small></label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="custom-select select2bs4" value="{{ $tksk != null ? $tksk->jenis_kelamin : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['L' => 'laki-laki', 'P' => 'perempuan'] as $key => $kelamin)
                                        <option value="{{ $key }}" {{ $tksk != null && $key == $tksk->jenis_kelamin ? 'selected' : '' }}>{{ $kelamin }}</option>
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

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_jalan"><small><b>Jalan</b></small></label>
                                <input type="text" class="form-control" id="alamat_jalan" name="alamat_jalan" value="{{ $tksk != null ? $tksk->alamat_jalan : '' }}">
                                <span id="alamat_jalan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_rt"><small><b>RT</b></small></label>
                                <input type="text" class="form-control" id="alamat_rt" name="alamat_rt" value="{{ $tksk != null ? $tksk->alamat_rt : '' }}">
                                <span id="alamat_rt-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_rw"><small><b>RW</b></small></label>
                                <input type="text" class="form-control" id="alamat_rw" name="alamat_rw" value="{{ $tksk != null ? $tksk->alamat_rw : '' }}">
                                <span id="alamat_rw-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alamat_kelurahan"><small><b>Kelurahan</b></small></label>
                                <input type="text" id="alamat_kelurahan_hide" name="alamat_kelurahan_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $tksk != null ? $tksk->alamat_kelurahan : '' }}">
                                <input type="text" class="form-control" id="alamat_kelurahan" name="alamat_kelurahan" value="{{ $tksk != null ? $tksk->alamat_kelurahan : '' }}">
                                <span id="alamat_kelurahan-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">REKENING</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama_di_rekening"><small><b>Nama Di Rekening</b></small></label>
                                <input type="text" class="form-control" id="nama_di_rekening" name="nama_di_rekening" value="{{ $tksk != null ? $tksk->nama_di_rekening : '' }}">
                                <span id="nama_di_rekening-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_rekening"><small><b>No. Rekening</b></small></label>
                                <input type="text" class="form-control" id="no_rekening" name="no_rekening" value="{{ $tksk != null ? $tksk->no_rekening : '' }}">
                                <span id="no_rekening-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama_bank"><small><b>Nama Bank</b></small></label>
                                <input type="text" id="nama_bank_hide" name="nama_bank_hide" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $tksk != null ? $tksk->nama_bank : '' }}">
                                <input type="text" class="form-control" id="nama_bank" name="nama_bank" value="{{ $tksk != null ? $tksk->nama_bank : '' }}">
                                <span id="nama_bank-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">PENGANGKATAN PERTAMA</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tahun_pengangkatan_pertama"><small><b>Tahun</b></small></label>
                                <input type="number" class="form-control" id="tahun_pengangkatan_pertama" name="tahun_pengangkatan_pertama" value="{{ $tksk != null ? $tksk->tahun_pengangkatan_pertama : '' }}">
                                <span id="tahun_pengangkatan_pertama-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nosk_pengangkatan_pertama"><small><b>No. SK</b></small></label>
                                <input type="text" class="form-control" id="nosk_pengangkatan_pertama" name="nosk_pengangkatan_pertama" value="{{ $tksk != null ? $tksk->nosk_pengangkatan_pertama : '' }}">
                                <span id="nosk_pengangkatan_pertama-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pejabat_pengangkatan_pertama"><small><b>Pejabat</b></small></label>
                                <input type="text" class="form-control" id="pejabat_pengangkatan_pertama" name="pejabat_pengangkatan_pertama" value="{{ $tksk != null ? $tksk->pejabat_pengangkatan_pertama : '' }}">
                                <span id="pejabat_pengangkatan_pertama-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="col">
                            <div class="d-flex justify-content-center align-items-center" style="border-bottom: 2px solid #D0D5DB;position: relative;">
                                <span class="p-4 text-bold text-muted" style="position: absolute;background: #F4F6F9;">PENGANGKATAN TERAKHIR</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tahun_pengangkatan_terakhir"><small><b>Tahun</b></small></label>
                                <input type="number" class="form-control" id="tahun_pengangkatan_terakhir" name="tahun_pengangkatan_terakhir" value="{{ $tksk != null ? $tksk->tahun_pengangkatan_terakhir : '' }}">
                                <span id="tahun_pengangkatan_terakhir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nosk_pengangkatan_terakhir"><small><b>No. SK</b></small></label>
                                <input type="text" class="form-control" id="nosk_pengangkatan_terakhir" name="nosk_pengangkatan_terakhir" value="{{ $tksk != null ? $tksk->nosk_pengangkatan_terakhir : '' }}">
                                <span id="nosk_pengangkatan_terakhir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pejabat_pengangkatan_terakhir"><small><b>Pejabat</b></small></label>
                                <input type="text" class="form-control" id="pejabat_pengangkatan_terakhir" name="pejabat_pengangkatan_terakhir" value="{{ $tksk != null ? $tksk->pejabat_pengangkatan_terakhir : '' }}">
                                <span id="pejabat_pengangkatan_terakhir-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    @if ($tksk == null || $user->level_id == 1)
                        <button type="button" class="w-100 mt-4 btn btn-success" onclick="saveData()">SIMPAN</button>
                    @elseif ($user->level_id == 2 && $tksk != null && $tksk->status == 'diperiksa')
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="w-100 mt-4 btn btn-danger" onclick="verifTKSK(this, event, 'ditolak', {{ $tksk->id }})">
                                    TOLAK
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="w-100 mt-4 btn btn-success" onclick="verifTKSK(this, event, 'diterima', {{ $tksk->id }})">
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
