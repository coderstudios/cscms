@extends('cscms::backend.layouts.master')

@section('page_title')
Capability
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            @if ($vars['form_type'] == 'create')
            <h1>New capability</h1>
            @endif
            @if ($vars['form_type'] == 'edit')
            <h1>Edit capability</h1>
            @endif

            @include('cscms::backend.partials.errors')

            <form method="post" action="{{ $vars['action'] }}">
                {!! csrf_field() !!}
                <input type="hidden" name="id" value="{{ $vars['id'] }}" />

                <div class="form-group row">
                    <label for="name" class="col-2 col-form-label">Name</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('name',$vars['capability']->name) }}" id="name" name="name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sort_order" class="col-2 col-form-label">Sort order</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('sort_order',$vars['capability']->sort_order) }}" id="sort_order" name="sort_order">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="enabled" class="col-2">Enabled</label>
                    <div class="col-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="enabled" value="1" {{ old('enabled',$vars['capability']->enabled) === 1 ? 'checked' : '' }}> Yes
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
