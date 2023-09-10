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
    <script src="{{ asset('js/plugins/bootstrap-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/pages/karang_taruna.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head', ['headTitle' => 'KARANG TARUNA', 'headPage' => 'karang taruna'])

	<div class="content mt-4">
		<div class="container-fluid">
            {{-- Button Row --}}
            <div class="row mb-4 px-2">
                <div class="col-12 d-flex align-items-end justify-content-end">
                    @if (in_array($user->level_id, [1,3]))
                    <a href="{{ route('karang_taruna.create') }}" class="btn btn-block btn-secondary btn-md mr-2" style="width: max-content;">
                        <i class="fa fa-plus"></i> &nbsp; tambah
                    </a>
                    @endif
                    @if (in_array($user->level_id, [1]))
                    <a href="" class="btn btn-block btn-secondary btn-md" style="width: max-content;" data-toggle="modal" data-target="#modal-import-karang_taruna">
                        <i class="fa fa-upload"></i> &nbsp; import
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
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
				<div class="col-md-10">
                    <div class="card card-secondary card-outline">
						<div class="card-header">
							<table id="tableKarangTaruna" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="align-middle">
											No
										</th>
										<th class="align-middle">
											No Urut
										</th>
										<th class="align-middle">
											Nama
										</th>
										<th class="align-middle">
											Nama Ketua
										</th>
                                        <th class="align-middle">
											Program Unggulan
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
					</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import-karang_taruna">
        <div class="modal-dialog">
            <form id="formImportKarangTaruna" class="modal-content" autocomplete="off">
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
                                <label for="site"><small><b>Site</b></small></label>
                                <input type="text" id="site_id" name="site_id" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                                <input type="text" class="form-control" id="site" name="site">
                            </div>
                            <div class="form-group mb-4">
                                <label for="year"><small><b>Tahun</b></small></label>
                                <select id="year" name="year" class="custom-select select2bs4">
                                    <option value="">-- pilih --</option>

                                    @for ($year = 2000; $year <= date('Y'); $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <span id="year-error" class="invalid-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label for="file_karang_taruna"><small><b>File</b></small></label>
                                <input type="file" class="form-control-file" id="file_karang_taruna" name="file_karang_taruna">
                                <span id="file_karang_taruna-error" class="error invalid-feedback" style="display: inline"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <a target="_blank" href="{{ asset('template_import/karang_taruna_import.xls') }}" class="btn btn-info">
                        <i class="nav-icon fa fa-download mr-1"></i>
                        Template
                    </a>
                    <button type="button" class="btn btn-success" onclick="saveImport()">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection
