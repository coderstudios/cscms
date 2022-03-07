@extends('cscms::backend.layouts.app')

@section('page_title')
Settings
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col content-container">

            @if ($vars['form_type'] == 'create')
            <h1>New setting</h1>
            @endif
            @if ($vars['form_type'] == 'edit')
            <h1>Edit setting</h1>
            @endif

            @include('cscms::backend.partials.errors')

            <form method="post" action="{{ $vars['action'] }}">
                {!! csrf_field() !!}
                <div class="form-group row">
                    <label for="class" class="col-2 col-form-label">Group</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('class',$vars['setting']->class) }}" id="class" name="class">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-2 col-form-label">Name</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('name',$vars['setting']->name) }}" id="name" name="name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="value" class="col-2 col-form-label">Value</label>
                    <div class="col-10">
                        <textarea class="form-control" id="value" name="value" rows="5">{{ old('value',$vars['setting']->value) }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="serialized" class="col-2">Serialized</label>
                    <div class="col-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="serialized" value="1" {{ old('serialized',$vars['setting']->serialized) === 1 ? 'checked' : '' }}> Yes
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
