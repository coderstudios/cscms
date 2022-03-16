@extends('cscms::backend.layouts.app')

@section('page_title')
Cache
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col content-container">
            <h1>Cache</h1>
            <div class="row">
                <div class="col col-md-2">
                    <div class="card">
                        <div class="card-block">
                            <h4>Image Cache</h4>
                            <p>Size: {{ $vars['image'] or 0 }}</p>
                            <form method="post" action="{{ route('backend.cache.images.clear') }}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="value" value="images" />
                                <button type="submit" class="btn btn-primary">Clear cache</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col col-md-2">
                    <div class="card">
                        <div class="card-block">
                            <h4>All Cache</h4>
                            <p>Size: {{ $vars['all'] or 0 }}</p>
                            <form method="post" action="{{ route('backend.cache.all.clear') }}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="value" value="all" />
                                <button type="submit" class="btn btn-primary">Clear cache</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col col-md-2">
                    <div class="card">
                        <div class="card-block">
                            <h4>Optimise classes</h4>
                            <p>&nbsp;</p>
                            <form method="post" action="{{ route('backend.cache.optimise.classes') }}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="value" value="optimise_url" />
                                <button type="submit" class="btn btn-primary">Optimise</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col col-md-2">
                    <div class="card">
                        <div class="card-block">
                            <h4>Optimise URLS</h4>
                            <p>&nbsp;</p>
                            <form method="post" action="{{ route('backend.cache.optimise.url') }}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="value" value="optimise_url" />
                                <button type="submit" class="btn btn-primary">Optimise</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col col-md-2">
                    <div class="card">
                        <div class="card-block">
                            <h4>Optimise config</h4>
                            <p>&nbsp;</p>
                            <form method="post" action="{{ route('backend.cache.optimise.config') }}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="value" value="optimise_config" />
                                <button type="submit" class="btn btn-primary">Optimise</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
