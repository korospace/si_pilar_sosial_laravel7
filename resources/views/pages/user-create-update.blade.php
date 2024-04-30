@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/pages/user-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'user.main'])

    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
				<form id="formCreateUpdateUser" class="col-12" autocomplete="off" style="position: relative;">
                    @if($userEdit != null)
                        <input type="text" id="id" name="id" value="{{ $userEdit->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif

                    <div class="form-group">
                        <label for="name">Nama User</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $userEdit != null ? $userEdit->name : '' }}">
                        <span id="name-error" class="invalid-feedback"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="{{ $userEdit != null ? $userEdit->email : '' }}">
                        <span id="email-error" class="invalid-feedback"></span>
                    </div>

                    <div class="form-group">
                        <label for="level_id">Akses</label>
                        <select id="level_id" name="level_id" class="custom-select" value="{{ $userEdit != null ? $userEdit->level_id : '' }}">
                            <option value="">-- pilih hak akses --</option>

                            @foreach ($userLevels as $uLevel)
                                <option value="{{ $uLevel->id }}" {{ $userEdit != null && $uLevel->id == $userEdit->level_id ? 'selected' : '' }}>{{ $uLevel->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group form-group-site">
                        <label for="site">Site</label>
                        <input type="text" id="site_id" name="site_id" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;" value="{{ $userEdit != null ? $userEdit->site_id : '' }}">
                        <input type="text" class="form-control" id="site" name="site" value="{{ $userEdit != null && $userEdit->site ? $userEdit->site->name : '' }}">
                    </div>

                    @if($userEdit == null)
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="with-icon">
                                <input type="password" class="form-control" id="password" name="password">
                                <i class="pass_hide nav-icon fa fa-eye opacity-07"></i>
                                <i class="pass_show nav-icon fa fa-eye-slash opacity-07"></i>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <label for="new_password">Password Baru <small>(opsional)</small></label>
                            <div class="with-icon">
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <i class="pass_hide nav-icon fa fa-eye opacity-07"></i>
                                <i class="pass_show nav-icon fa fa-eye-slash opacity-07"></i>
                            </div>
                        </div>
                    @endif

                    <button type="button" class="w-100 mt-4 btn btn-success" onclick="saveData()">SIMPAN</button>
                </form>
            </div>
        </div>
    </div>
@endsection
