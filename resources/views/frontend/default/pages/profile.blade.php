@extends('cscms::frontend.'.$vars['theme'].'.layouts.app')

@section('page_title')
Profile
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col-sm-12">

            <h1>Profile</h1>

            @include('cscms::frontend.partials.errors')

            <form method="post" action="{{ $vars['action'] }}">
                {!! csrf_field() !!}
                <input type="hidden" name="id" value="{{ $vars['user']->id }}" />

                <div class="form-group row">
                    <label for="username" class="col-2 col-form-label">Joined</label>
                    <div class="col-10">
	                    {{ $vars['user']->created_at->format('d-m-Y') }}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="username" class="col-2 col-form-label">Username</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ $vars['user']->username }}" readonly id="username" name="username">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-2 col-form-label">Name</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('name',$vars['user']->name) }}" id="name" name="name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-2 col-form-label">Email</label>
                    <div class="col-10">
                        <input class="form-control" type="email" value="{{ old('email',$vars['user']->email) }}" id="email" name="email" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection
