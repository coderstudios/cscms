@extends('cscms::backend.layouts.master')

@section('page_title')
Export
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Export</h1>

            <p><a href="{{ route('backend.export.capabilities') }}" class="btn btn-primary">Capabilities</a></p>

            <p><a href="{{ route('backend.export.settings') }}" class="btn btn-primary">Settings</a></p>

            <p><a href="{{ route('backend.export.users') }}" class="btn btn-primary">Users</a></p>

            <p><a href="{{ route('backend.export.user_roles') }}" class="btn btn-primary">User roles</a></p>

            <p><a href="{{ route('backend.export.email') }}" class="btn btn-primary">Emails</a></p>

            <h1>Import</h1>

            <p><a href="{{ route('backend.import', ['type' => 'capabilities']) }}" class="btn btn-primary">Capabilities</a></p>

            <p><a href="{{ route('backend.import', ['type' => 'settings']) }}" class="btn btn-primary">Settings</a></p>

            <p><a href="{{ route('backend.import', ['type' => 'users']) }}" class="btn btn-primary">Users</a></p>

            <p><a href="{{ route('backend.import', ['type' => 'user_roles']) }}" class="btn btn-primary">User roles</a></p>

        </div>

    </div>

</div>

@endsection