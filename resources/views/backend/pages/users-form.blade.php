@extends('cscms::backend.layouts.master')

@section('page_title')
Users
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            @if ($vars['form_type'] == 'create')
            <h1>New user</h1>
            @endif
            @if ($vars['form_type'] == 'edit')
            <h1>Edit user</h1>
            <p class="text-right">
                <a class="btn btn-info" href="{{ route('backend.users.user.resendverify',$vars['user']->id) }}">Resend verify email</a>
            </p>
            @endif

            @include('backend.partials.errors')

            <form method="post" action="{{ $vars['action'] }}">
                {!! csrf_field() !!}
                <input type="hidden" name="id" value="{{ $vars['id'] }}" />

                <div class="form-group row">
                    <label for="name" class="col-2 col-form-label">Name</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('name',$vars['user']->name) }}" id="name" name="name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="username" class="col-2 col-form-label">Username</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('username',$vars['user']->username) }}" id="username" name="username">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-2 col-form-label">Email</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('email',$vars['user']->email) }}" id="email" name="email">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-2 col-form-label">Password</label>
                    <div class="col-10">
                        <input class="form-control" type="password" id="password" name="password">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password_confirmation" class="col-2 col-form-label">Confirm password</label>
                    <div class="col-10">
                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="user_role_id" class="col-2 col-form-label">User role</label>
                    <div class="col-10">
                        <select name="user_role_id" id="user_role_id" class="form-control">
                            <option value="">Please select...</option>
                            @foreach($vars['user_roles'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == old('user_role_id',$vars['user']->user_role_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="enabled" class="col-2">Enabled</label>
                    <div class="col-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="enabled" value="1" {{ old('enabled',$vars['user']->enabled) === 1 ? 'checked' : '' }}> Yes
                            </label>
                        </div>
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
