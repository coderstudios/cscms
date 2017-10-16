@extends('backend.layouts.master')

@section('page_title')
Capabilities
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Capabilities</h1>

            <p class="text-right"><a href="{{ route('backend.capabilities.create')}}" class="btn btn-primary">New capability</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Enabled</th>
                        <th>Sort order</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['capabilities']))
                    @foreach($vars['capabilities'] as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->enabled === 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('backend.capabilities.capability.edit' , ['id' => $item->id ]) }}" class="btn">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this capability?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.capabilities.capability.delete' , ['id' => $item->id ]) }}" class="btn">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No capabilities found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['capabilities']))
            {!! $vars['capabilities']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection
