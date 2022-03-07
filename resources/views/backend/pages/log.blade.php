@extends('cscms::backend.layouts.app')

@section('page_title')
Error log
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col content-container">

            <h1>Error log</h1>

            <form method="post" action="{{ route('backend.clear-log') }}">
                {!! csrf_field() !!}

                <div class="form-group">
                    <textarea class="form-control" readonly rows="20" style="width:100%; overflow:auto;">{{ $vars['log'] }}</textarea>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-warning">Clear log</button>
                </div>
            </form>

        </div>

    </div>

</div>

@endsection
