@extends('cscms::backend.layouts.master')

@section('page_title')
Email Groups
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col content-container">

            <h1>Email groups</h1>

            <p class="text-right"><a href="{{ route('backend.email_groups.create')}}" class="btn btn-primary">New email group</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Enabled</th>
                        <th>Sort Order</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['email_groups']))
                    @foreach($vars['email_groups'] as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->enabled === 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('backend.email_groups.email_group.edit' , ['id' => $item->id ]) }}" class="btn btn-sm btn-primary">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this email group?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.email_groups.email_group.delete' , ['id' => $item->id ]) }}" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No email groups found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['email_groups']))
            {!! $vars['email_groups']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection