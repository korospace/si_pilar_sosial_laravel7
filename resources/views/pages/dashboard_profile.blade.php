@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard_profile.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'dashboard.main'])

    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
				<form id="formEditProfile" class="col-12" autocomplete="off" style="position: relative;">
                    <input type="text" id="id" name="id" value="{{ $user->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">

                    @if ($user->site)
                    <div class="form-group mb-4">
                        <label for="site">Site</label>
                        <input type="text" class="form-control" id="site" name="site" value="{{ $user->site ? $user->site->name : '' }}" disabled>
                    </div>
                    @endif

                    <div class="form-group mb-4">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                        <span id="name-error" class="invalid-feedback"></span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}">
                        <span id="email-error" class="invalid-feedback"></span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="new_password">Password Baru <small>(opsional)</small></label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>

                    <button type="button" class="w-100 mt-4 btn btn-success" onclick="saveData()">SIMPAN</button>
                </form>
            </div>
        </div>
    </div>
@endsection
