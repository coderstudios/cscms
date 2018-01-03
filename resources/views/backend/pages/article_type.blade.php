@extends('cscms::backend.layouts.master')

@section('page_title')
Article Types
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Article Types</h1>

            <p class="text-right"><a href="{{ route('backend.article_types.article_type.create')}}" class="btn btn-primary">New article type</a></p>

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Enabled</th>
                        <th>Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['article_types']))
                    @foreach($vars['article_types'] as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->enabled ===1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            <a href="{{ route('backend.article_types.article_type.edit' , ['id' => $item->id ]) }}" class="btn btn-sm btn-primary">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this article type?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.article_types.article_type.delete' , ['id' => $item->id ]) }}" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No article types found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['article_types']))
            {!! $vars['article_types']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection