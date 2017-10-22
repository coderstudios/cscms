@extends('cscms::backend.layouts.master')

@section('page_title')
Users
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Users</h1>

            <p class="text-right"><a href="{{ route('backend.users.create')}}" class="btn btn-primary">New user</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Verified</th>
                        <th>Enabled</th>
                        <th>Group</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['users']))
                    @foreach($vars['users'] as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->verified === 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $item->enabled === 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $item->role->name }}</td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('backend.users.user.edit' , ['id' => $item->id ]) }}" class="btn">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this user?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.users.user.delete' , ['id' => $item->id ]) }}" class="btn">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No users found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['users']))
            {!! $vars['users']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection