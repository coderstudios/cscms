@extends('cscms::backend.layouts.master')

@section('page_title')
Articles
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            <h1>Articles</h1>

            <p class="text-right"><a href="{{ route('backend.articles.article.create')}}" class="btn btn-primary">New article</a></p>

            @if (count($vars['articles']))
            <p>Displaying {{ count($vars['articles']) }} articles of {{ $vars['total_articles_count'] }}</p>
            @endif

            <table class="table table-bordered table-hover table-sm">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Type</th>
                        <th>Publish</th>
                        <th>Enabled</th>
                        <th>Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (count($vars['articles']))
                    @foreach($vars['articles'] as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->slug }}</td>
                        <td>{{ $item->type->name }}</td>
                        <td>{{ (strlen($item->publish_at)) ? $item->publish_at : '' }}</td>
                        <td>{{ $item->enabled ===1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            <a href="{{ route('backend.articles.article.edit' , ['id' => !empty($item->latest_revision_id) ? $item->latest_revision_id : $item->id ]) }}" class="btn">Edit</a>
                            <a onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this article?')){document.getElementById('delete-form').setAttribute('action',this.href); document.getElementById('delete-form').submit();}" href="{{ route('backend.articles.article.delete' , ['id' => !empty($item->latest_revision_id) ? $item->latest_revision_id : $item->id ]) }}" class="btn">Delete</a>
                            </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No articles found</td>
                    </tr>
                @endif

            </table>
            <form id="delete-form" action="#" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            @if (count($vars['articles']))
            {!! $vars['articles']->links() !!}
            @endif

        </div>

    </div>

</div>

@endsection