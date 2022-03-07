@extends('cscms::backend.layouts.app')

@section('page_title')
Emails
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col content-container">

            <h1>Emails</h1>

            <p class="text-right"><a href="{{ route('backend.emails.create')}}" class="btn btn-primary">New email</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Enabled</th>
                        <th>Groups</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['emails']))
                    @foreach($vars['emails'] as $item)
                    <tr>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->enabled === 1 ? 'Yes' : 'No' }}</td>
                        <td>
                            @if ($item->groups)
                                @foreach($item->groups as $group)
                                    <span class="badge badge-default">{{ $group->name }}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td><a href="{{ route('backend.emails.email.edit' , ['id' => $item->id ]) }}" class="btn btn-sm btn-primary">Edit</a></td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No emails found</td>
                    </tr>
                @endif

            </table>

            @if (count($vars['emails']))
            {!! $vars['emails']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection