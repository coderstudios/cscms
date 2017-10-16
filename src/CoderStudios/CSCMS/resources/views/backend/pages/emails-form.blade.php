@extends('backend.layouts.master')

@section('page_title')
Email
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            @if ($vars['form_type'] == 'create')
            <h1>New email</h1>
            @endif
            @if ($vars['form_type'] == 'edit')
            <h1>Edit email</h1>
            @endif

            @include('backend.partials.errors')

            <form method="post" action="{{ $vars['action'] }}">
                {!! csrf_field() !!}

                <div class="form-group row">
                    <label for="email" class="col-2 col-form-label">Email</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('email',$vars['email']->email) }}" id="email" name="email">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email_groups" class="col-2 col-form-label">Groups</label>
                    <div class="col-10">
                       @foreach($vars['groups'] as $item)
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="email_groups[]" {{ in_array($item->id,$vars['email']->groups->pluck('id')->toArray()) ? 'checked' : '' }} value="{{ $item->id }}"> {{ $item->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group row">
                    <label for="enabled" class="col-2">Enabled</label>
                    <div class="col-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="enabled" value="1" {{ old('enabled',$vars['email']->enabled) === 1 ? 'checked' : '' }}> Yes
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