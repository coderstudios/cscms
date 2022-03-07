@extends('cscms::backend.layouts.app')

@section('page_title')
Backups
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col content-container">

            <h1>Backups</h1>

            <div class="row">

                <div class="col">

                    @if(count($vars['backups']))
                        <div class="card">
                            <div class="card-block">
                                @foreach($vars['backups'] as $backup)
                                    <p>
                                        <a href="{{ route('backend.backups.backup.delete' , ['id' => $backup['id'] ] ) }}"><i class="fa fa-trash" title="Delete"></i></a> | <a href="{{ route('backend.download', ['type' => 'backup', 'id' => $backup['id'] ]) }}"><i class="fa fa-download" title="Download file"></i></a> {{ $backup['name'] }} : {{ $backup['size'] }}

                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>

            <div class="row">

                <div class="col">

                    <form method="post" action="{{ route('backend.backups.backup') }}">
                        {!! csrf_field() !!}
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-warning">Create backup</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
