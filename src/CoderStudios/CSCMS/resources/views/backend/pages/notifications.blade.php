@extends('backend.layouts.master')

@section('page_title')
Notifications
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Notifications</h1>

            <p class="text-right"><a href="{{ route('backend.notifications.create')}}" class="btn btn-primary">New notification</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Enabled</th>
                        <th>Publish at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['notifications']))
                    @foreach($vars['notifications'] as $item)
                    <tr>
                        <td>{{ $item->subject }}</td>
                        <td>{{ $item->enabled === 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ !empty($item->publish_at) ? $item->publish_at->format('d-m-Y H:i') : 'Published' }}</td>
                        <td>
                            <a href="{{ route('backend.notifications.notification.edit' , ['id' => $item->id ]) }}" class="btn">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this notification?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.notifications.notification.delete' , ['id' => $item->id ]) }}" class="btn">Delete</a>
                            </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No notifications found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['notifications']))
            {!! $vars['notifications']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection