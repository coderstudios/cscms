@extends('backend.layouts.master')

@section('page_title')
User Roles
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>User Roles</h1>

            <p class="text-right"><a href="{{ route('backend.user_roles.create')}}" class="btn btn-primary">New user role</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Enabled</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['user_roles']))
                    @foreach($vars['user_roles'] as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->enabled === 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('backend.user_roles.user_role.edit' , ['id' => $item->id ]) }}" class="btn">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this user role?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.user_roles.user_role.delete' , ['id' => $item->id ]) }}" class="btn">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No user roles found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['user_roles']))
            {!! $vars['user_roles']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection
