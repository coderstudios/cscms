@extends('backend.layouts.master')

@section('page_title')
Images
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Images</h1>

            <p class="text-right"><a href="{{ route('backend.images.image.create')}}" class="btn btn-primary">New image</a></p>

            <div class="row">

            @if (count($vars['images']))

                @foreach($vars['images'] as $item)

                    @if ($loop->iteration > 4)
                        @if ($loop->iteration % 4 == 0)
                            </div>
                            <hr />
                            <div class="row">
                        @endif
                    @endif

                <div class="col-3">
                    <div class="card">
                        <div class="card-block">
                            <div class="text-right"><a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this image?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.images.image.delete',['id' => $item->id]) }}"><span class="fa fa-trash"></span></a></div>
                        </div>
                        <img class="card-img-top" src="{{ route('backend.image', [ 'filename' => $item->generated_filename, 'width' => '245', 'height' => '200' ])}}" alt="" />
                        <div class="card-block">
                            <ul class="list-unstyled">
                                <li>Added: {{ $item->created_at->format('d.m.Y') }}</li>
                                <li style="overflow:hidden;">{{ $item->filename }}</li>
                                <li><input class="form-control" type="text" value="{{ $item->generated_filename }}"></li>
                            </ul>
                        </div>
                    </div>
                </div>

                    @if ($loop->iteration >= 4)
                        @if ($loop->iteration % 4 == 0)
                            </div>
                            <hr />
                            <div class="row">
                        @endif
                    @endif

                @endforeach

            @else
                <div class="col-12">
                    <p>No images found.</p>
                </div>
            @endif

            </div>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>


            @if (count($vars['images']))
            {!! $vars['images']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection