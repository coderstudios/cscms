@extends('backend.layouts.master')

@section('page_title')
Langauges
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Languages</h1>

            <p class="text-right"><a href="{{ route('backend.languages.language.create')}}" class="btn btn-primary">New language</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Enabled</th>
                        <th>Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['languages']))
                    @foreach($vars['languages'] as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->enabled ===1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            <a href="{{ route('backend.languages.language.edit' , ['id' => $item->id ]) }}" class="btn">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this language?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.languages.language.delete' , ['id' => $item->id ]) }}" class="btn">Delete</a>
                            </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No languages found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['languages']))
            {!! $vars['languages']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection