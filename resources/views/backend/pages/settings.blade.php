@extends('cscms::backend.layouts.master')

@section('page_title')
Settings
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Settings</h1>

            <p class="text-right"><a href="{{ route('backend.settings.setting.create')}}" class="btn btn-primary">New setting</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['settings']))
                    @foreach($vars['settings'] as $item)
                    <tr>
                        <td>{{ $item->class }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->serialized === 1 ? '[serialized...]' : substr($item->value,0,12) }}{{ strlen($item->value) > 12 && $item->serialized === 0 ? '...':'' }}</td>
                        <td>
                            <a href="{{ route('backend.settings.setting.edit' , ['id' => $item->id ]) }}" class="btn btn-sm btn-primary">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this setting?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.settings.setting.delete' , ['id' => $item->id ]) }}" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No settings found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['settings']))
            {!! $vars['settings']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection
