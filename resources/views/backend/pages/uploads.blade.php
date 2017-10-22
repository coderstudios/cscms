@extends('cscms::backend.layouts.master')

@section('page_title')
Uploads
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Uploads</h1>

            <p class="text-right"><a href="{{ route('backend.uploads.upload.create')}}" class="btn btn-primary">New upload</a></p>

            @if (count($vars['uploads']))

                @foreach($vars['uploads'] as $item)

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-block">
                                <div class="text-right"><a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this upload?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.images.image.delete',['id' => $item->id]) }}"><span class="fa fa-trash"></span></a></div>
                                <ul class="list-unstyled">
                                    <li>Added: {{ $item->created_at->format('d.m.Y') }}</li>
                                    <li>{{ $item->filename }}</li>
                                    <li><input class="form-control" type="text" value="{{ $item->generated_filename }}"></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                @endforeach

            @else
                <div class="col-12">
                    <p>No uploads found.</p>
                </div>
            @endif

            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>


            @if (count($vars['uploads']))
            {!! $vars['uploads']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection