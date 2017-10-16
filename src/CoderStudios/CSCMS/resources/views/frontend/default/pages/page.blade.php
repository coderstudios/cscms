@extends('frontend.default.layouts.master')

@section('page_title')
{{ $vars['article']->title or '' }}
@endsection

@section('metas')
<meta name="description" value="{{ $vars['article']->meta_description or '' }}">
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col-sm-12">

            <h1>{{ $vars['article']->title or '' }}</h1>

            {!! $vars['description']->content !!}

        </div>

    </div>

</div>

@endsection
