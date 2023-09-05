@extends('layouts.dashboard-wraper')

@push('dashboard-wraper.css')
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/summernote/summernote-bs4.css') }}">
@endpush

@push('dashboard-wraper.jscript')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/pages/crud-article-create-update.js') }}"></script>
@endpush

@section('dashboard-wraper.content')
    @include('components/dashboard-head-backbtn', ['headTitle' => $headTitle, 'headBackUrl' => 'crudarticle.main'])

    <div class="content mt-4">
		<div class="container-fluid">
            <div class="row px-2">
				<form id="formCreateUpdateArticle" class="col-12 pb-5" autocomplete="off" style="position: relative;">
                    @if($article != null)
                        <input type="text" id="id" name="id" value="{{ $article->id }}" style="position: absolute;z-index: -10;opacity: 0;max-width: 0px;">
                    @endif

                    <div class="row">
                        <div class="col-sm-10 col-md-8 col-xl-6">
                            <img id="thumbnail_preview" src="{{ $article != null ? $article->public_thumbnail : asset('images/default-thumbnail.webp') }}" alt="default thumbnail" class="w-100 img-thumbnail">
                        </div>
                        <div class="col-12 mt-4">
                            @if($article == null)
                            <div class="form-group">
                                <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" value="form-control-file" onchange="changeThumbPreview(this);">
                                <span id="thumbnail-error" class="invalid-feedback"></span>
                            </div>
                            @else
                            <div class="form-group">
                                <input type="file" class="form-control-file" id="newthumbnail" name="newthumbnail" value="form-control-file" onchange="changeThumbPreview(this);">
                                <span id="newthumbnail-error" class="invalid-feedback"></span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr class="overlay">

                    <div class="row mt-4">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="title">Judul</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $article != null ? $article->title : '' }}">
                                <span id="title-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="custom-select" value="{{ $article != null ? $article->status : '' }}">
                                    <option value="">-- pilih --</option>

                                    @foreach (['draft', 'release'] as $status)
                                        <option value="{{ $status }}" {{ $article != null && $status == $article->status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group release_date_wraper {{ $article != null && $article->status == 'release' ? '' : 'd-none' }}">
                                <label for="release_date"><small><b>Tanggal Release</b></small></label>
                                <input type="text" class="form-control tgl" id="release_date" name="release_date" value="{{ $article != null && $article->release_date ? date("d-m-Y H:i", $article->release_date) : '' }}">
                                <span id="release_date-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="form-group">
                                <textarea id="body" name="body">{{ $article != null ? $article->body : '' }}</textarea>
                                <span id="title-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="w-100 mt-4 btn btn-success" onclick="saveData()">SIMPAN</button>
                </form>
            </div>
        </div>
    </div>
@endsection

