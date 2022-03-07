@extends('cscms::backend.layouts.app')

@section('page_title')
Error log
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col-sm-12 content-container">

            <h1>PHPINFO</h1>

            <div class="card" style="height:400px; overflow:auto;">

                <div class="card-block">
                {!! $vars['phpinfo'] !!}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
