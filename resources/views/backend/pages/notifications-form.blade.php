@extends('cscms::backend.layouts.master')

@section('page_title')
Notification
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col content-container">

            @if ($vars['form_type'] == 'create')
            <h1>New notification</h1>
            @endif
            @if ($vars['form_type'] == 'edit')
            <h1>Edit notification</h1>
            @endif

            @include('cscms::backend.partials.errors')

            <form method="post" action="{{ $vars['action'] }}">
                {!! csrf_field() !!}

                <div class="form-group row">
                    <label for="subject" class="col-2 col-form-label">Subject</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('subject',$vars['notification']->subject) }}" id="subject" name="subject">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sort_order" class="col-2 col-form-label">Sort order</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('sort_order',$vars['notification']->sort_order) }}" id="sort_order" name="sort_order">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="publish_at" class="col-2 col-form-label">Publish at</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('publish_at',$vars['notification']->publish_at) }}" id="publish_at" name="publish_at">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="message" class="col-2 col-form-label">Message</label>
                    <div class="col-10">
                        <textarea class="form-control" name="message" id="message" rows="5">{{ old('message',$vars['notification']->message) }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="enabled" class="col-2">Enabled</label>
                    <div class="col-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="enabled" value="1" {{ old('enabled',$vars['notification']->enabled) === 1 ? 'checked' : '' }}> Yes
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
