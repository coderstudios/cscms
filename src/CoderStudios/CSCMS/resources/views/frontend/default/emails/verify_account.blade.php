@extends('frontend.default.layouts.email')

@section('logo')

@endsection

@section('subject')
Verify account
@endsection

@section('content')

<p>Hi,</p>

<p>Please verify your account by following this link; <a href="{{ route('frontend.verify', ['token' => $vars['token'] ]) }}">verify account now</a> or copy and paste this link into your browser; {{ route('frontend.verify', ['token' => $vars['token'] ]) }}</p>

<p>Thanks</p>
@endsection