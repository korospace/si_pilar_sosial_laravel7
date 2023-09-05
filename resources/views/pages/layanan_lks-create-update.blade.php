@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/pages/layanan_lks-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'layanan_lks.main'])

    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
				<form id="formCreateUpdateLayananLks" class="col-12" autocomplete="off" style="position: relative;">
                    @if($LayananLks != null)
                        <input type="text" id="id" name="id" value="{{ $LayananLks->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif

                    <div class="form-group">
                        <label for="name">Nama Layanan</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $LayananLks != null ? $LayananLks->name : '' }}">
                        <span id="name-error" class="invalid-feedback"></span>
                    </div>

                    <button type="submit" class="w-100 mt-4 btn btn-success">SIMPAN</button>
                </form>
            </div>
        </div>
    </div>
@endsection
