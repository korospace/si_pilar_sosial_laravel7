@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/pages/site-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'site.main'])

    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
				<form id="formCreateUpdateSite" class="col-12" autocomplete="off" style="position: relative;">
                    @if($site != null)
                        <input type="text" id="id" name="id" value="{{ $site->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif

                    <div class="form-group">
                        <label for="name">Nama Site</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $site != null ? $site->name : '' }}">
                        <span id="name-error" class="invalid-feedback"></span>
                    </div>

                    <div class="form-group" style="display: {{ $site != null ? 'none' : '' }};">
                        <label for="region_id">Kode Wilayah</label>
                        <input type="text" class="form-control" id="region_id" name="region_id" value="{{ $site != null ? $site->region_id : '' }}">
                        <span id="region_id-error" class="invalid-feedback"></span>
                    </div>

                    <div class="form-group" style="display: {{ $site != null ? '' : 'none' }};">
                        <label for="">Kode Wilayah</label>
                        <input type="text" class="form-control" id="" name="" value="{{ $site != null ? $site->region_id : '' }}" disabled>
                    </div>

                    <button type="button" class="w-100 mt-4 btn btn-success" onclick="saveData()">SIMPAN</button>
                </form>
            </div>
        </div>
    </div>
@endsection
