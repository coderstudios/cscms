@extends('cscms::backend.layouts.master')

@section('page_title')
Import
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col content-container">

            <h1>Import</h1>

            <form method="post" action="{{ route('backend.import') }}" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <input type="hidden" name="type" value="{{ $vars['type'] }}" />

                <div class="form-check row">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="replace" value="1" {{ old('replace',$vars['replace']) === '1' ? 'checked' : '' }}> Replace existing data
                    </label>
                </div>

                <div class="form-group row">
                    <input class="form-control-file" type="file" name="import">
                </div>

                <div class="form-group row text-right">
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection
