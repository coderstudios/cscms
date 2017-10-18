@extends('backend.layouts.email')

@section('logo')

@endsection

@section('subject')
New user
@endsection

@section('content')

<p>Hi,</p>

<p>There is a new user, {{ $vars['name'] }}</p>

<ul>
    <li>Email: {{ $vars['email'] }}</li>
    <li>IP: {{ $vars['ipaddress'] }}</li>
</ul>

<p>Thanks</p>
@endsection