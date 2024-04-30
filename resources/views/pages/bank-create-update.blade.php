@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/pages/bank-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'bank.main'])

    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
				<form id="formCreateUpdateBank" class="col-12" autocomplete="off" style="position: relative;">
                    @if($bank != null)
                        <input type="text" id="id" name="id" value="{{ $bank->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif

                    <div class="form-group">
                        <label for="name">Nama Bank</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $bank != null ? $bank->name : '' }}">
                        <span id="name-error" class="invalid-feedback"></span>
                    </div>

                    <div class="form-group">
                        <label for="code">Code Bank</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ $bank != null ? $bank->code : '' }}">
                        <span id="code-error" class="invalid-feedback"></span>
                    </div>

                    <button type="submit" class="w-100 mt-4 btn btn-success">SIMPAN</button>
                </form>
            </div>
        </div>
    </div>
@endsection
