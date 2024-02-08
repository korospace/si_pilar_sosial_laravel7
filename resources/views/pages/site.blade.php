@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/datatable/buttons.bootstrap4.min.css') }}">
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/pages/site.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head', ['headTitle' => 'Master Wilayah', 'headPage' => 'Wilayah'])

	<div class="content mt-4">
		<div class="container-fluid">
            {{-- Button Row --}}
            <div class="row mb-4 px-2">
				<div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('site.create') }}" class="btn btn-block btn-secondary btn-md" style="width: max-content;">
						<i class="fa fa-plus"></i> &nbsp; tambah
                    </a>
				</div>
			</div>

            <div class="row px-2">
				<div class="col-12">
                    <div class="card card-secondary card-outline">
						<div class="card-header">
							<table id="tableSite" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>
											No
										</th>
										<th class="text-center">
											Nama
										</th>
										<th>
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
@endsection
