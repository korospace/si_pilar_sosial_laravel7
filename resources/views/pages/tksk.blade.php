@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/pages/tksk.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head', ['headTitle' => 'TENAGA KESEJAHTERAAN SOSIAL', 'headPage' => 'tksk'])

	<div class="content mt-4">
        <input type="hidden" id="level_id" value="{{ $user->level_id }}">

		<div class="container-fluid">
            {{-- Button Row --}}
            <div class="row mb-4 px-2">
                <div class="col-12 d-flex align-items-end justify-content-end">
                    @if (in_array($user->level_id, [1,3]))
                    <a href="{{ route('tksk.create') }}" class="btn btn-block btn-secondary btn-md mr-2" style="width: max-content;">
                        <i class="fa fa-plus"></i> &nbsp; tambah
                    </a>
                    @endif
                    @if (in_array($user->level_id, [1,3]))
                    <a href="" class="btn btn-block btn-secondary btn-md btn_import" style="width: max-content;" data-toggle="modal" data-target="#modal-import-tksk">
                        <i class="fa fa-upload"></i> &nbsp; import
                    </a>
                    @endif
                    @if (in_array($user->level_id, [1]))
                    <a href="" id="download_excel" class="btn btn-block btn-info btn-md ml-2" style="width: max-content;">
                        <i class="fa fa-download"></i> &nbsp; download
                    </a>
                    @endif
                </div>
            </div>

            <div class="row px-2">
                <div class="col-md-2">
                    <div class="card card-secondary card-outline text-secondary text-center p-2 mb-4">
                        <b style="">Info Status</b>
                    </div>

                    <div class="card">
                        <div class="card-body p-0">
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item active">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-list mr-2"></i> Total
                                        <span id="status_total" class="badge bg-secondary float-right">0</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="text-warning fa fa-exclamation-triangle mr-2"></i> Diperiksa
                                        <span id="status_diperiksa" class="badge bg-warning float-right">0</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="text-success fa fa-calendar-check" style="font-size: 18px;margin-right: 12px;"></i> Diterima
                                        <span id="status_diterima" class="badge bg-success float-right">0</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="text-danger fas fa-times-circle" style="font-size: 17px;margin-right: 10px;"></i> Ditolak
                                        <span id="status_ditolak" class="badge bg-danger float-right">0</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="text-info fas fa-ban" style="font-size: 17px;margin-right: 10px;"></i> Nonaktif
                                        <span id="status_nonaktif" class="badge bg-info float-right">0</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
				<div class="col-md-10">

                    <div class="card card-secondary card-outline">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-tableLks" data-toggle="pill" href="#div_aktif" role="tab" aria-selected="true">Aktif</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-tableLks_nonaktif" data-toggle="pill" href="#div_nonaktif" role="tab">Non-Aktif</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            {{-- filter --}}
                            <div class="mb-4">
                                <div class="wraper-filter">
                                    <button class="btn-filter" data-uniqid="modal-filter-tksk"> <!-- main.css -->
                                        <i class="fa fa-sliders-h opacity-07"></i>
                                    </button>
                                    <span class="ket-filter opacity-07">
                                    </span>
                                </div>
                            </div>

                            {{-- table --}}
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade show active" id="div_aktif" role="tabpanel">
                                    <table id="tableTksk" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="align-middle">
                                                    No
                                                </th>
                                                <th class="align-middle">
                                                    Tahun
                                                </th>
                                                <th class="align-middle">
                                                    NTR
                                                </th>
                                                <th class="align-middle">
                                                    No Induk Anggota
                                                </th>
                                                <th class="align-middle">
                                                    Nama
                                                </th>
                                                <th class="align-middle">
                                                    Tempat Tugas
                                                </th>
                                                <th class="align-middle">
                                                    Kelamin
                                                </th>
                                                <th class="align-middle">
                                                    Status
                                                </th>
                                                <th class="align-middle">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade show" id="div_nonaktif" role="tabpanel">
                                    <table id="tableTksk_nonaktif" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="align-middle">
                                                    No
                                                </th>
                                                <th class="align-middle">
                                                    Tahun
                                                </th>
                                                <th class="align-middle">
                                                    NTR
                                                </th>
                                                <th class="align-middle">
                                                    No Induk Anggota
                                                </th>
                                                <th class="align-middle">
                                                    Nama
                                                </th>
                                                <th class="align-middle">
                                                    Tempat Tugas
                                                </th>
                                                <th class="align-middle">
                                                    Kelamin
                                                </th>
                                                <th class="align-middle">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal import --}}
    <div class="modal fade" id="modal-import-tksk">
        <div class="modal-dialog">
            <form id="formImportTksk" class="modal-content" autocomplete="off">
                <div class="modal-header">
                    <h4 class="modal-title">Import File</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="alert alert-success alert-dismissible w-100">
                            <button type="button" class="close">&times;</button>
                            <span></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" autocomplete="off" style="position: relative;">
                            <div class="form-group mb-4">
                                <label for="site"><small><b>Wilayah</b></small></label>
                                @if (in_array($user->level_id, [1]))
                                <input type="text" id="site_id" name="site_id" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                                <input type="text" class="form-control ac_site" id="site" name="site" data-elementforvalue="#site_id">
                                @else
                                <input type="text" class="form-control" id="site" name="" value="{{ $user->site->name }}">
                                @endif
                            </div>
                            <div class="form-group mb-4">
                                <label for="year"><small><b>Tahun</b></small></label>
                                <select id="year" name="year" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <span id="year-error" class="invalid-feedback"></span>
                            </div>
                            @if (in_array($user->level_id, [1]))
                            <div class="form-group mb-4">
                                <label for="status"><small><b>Status</b></small></label>
                                <select id="status" name="status" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @foreach (['diperiksa','diterima','ditolak','nonaktif'] as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                                <span id="status-error" class="invalid-feedback"></span>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="file_tksk"><small><b>File</b></small></label>
                                <input type="file" class="form-control-file" id="file_tksk" name="file_tksk">
                                <span id="file_tksk-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <a target="_blank" href="{{ asset('template_import/tksk_import.xls') }}" class="btn btn-info">
                        <i class="nav-icon fa fa-download mr-1"></i>
                        Template
                    </a>
                    <button type="button" class="btn btn-success" onclick="saveImport()">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    {{-- modal filter --}}
    <div id="modal-filter-tksk" class="modal fade modal-filter">
        <div class="modal-dialog">
            <form id="formFilterTksk" class="modal-content" autocomplete="off">
                <div class="modal-header">
                    <h4 class="modal-title">Filter Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" autocomplete="off" style="position: relative;">
                            <div class="form-group mb-4">
                                <label for="year_filter"><small><b>Tahun</b></small></label>
                                <select id="year_filter" name="year_filter" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="site_filter"><small><b>Wilayah</b></small></label>
                                <input
                                    type="text" id="site_filter_id" name="site_filter_id"
                                    style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;"
                                    value="{{ $user->level_id != 1 ? $user->site->id : '' }}"
                                >
                                <input
                                    type="text" id="site_filter" name="site_filter"
                                    class="form-control {{ $user->level_id == 1 ? 'ac_site' : '' }}"
                                    value="{{ $user->level_id != 1 ? $user->site->name : '' }}"
                                    {{ $user->level_id != 1 ? 'disabled' : '' }}
                                >
                            </div>
                            <div id="status_filter_wraper" class="form-group mb-4">
                                <label for="status_filter"><small><b>Status</b></small></label>
                                <select id="status_filter" name="status_filter" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @foreach (['diperiksa','ditolak','diterima'] as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-info btn-clear-filter" onclick="clear_filter_tksk()">Clear</button>
                    <button type="button" class="btn btn-success" onclick="run_filter_tksk()">Submit</button>
                </div>
            </form>
        </div>
    </div>

@endsection
