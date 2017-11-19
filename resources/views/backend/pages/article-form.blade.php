@extends('cscms::backend.layouts.master')

@section('page_title')
Article
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            @if ($vars['form_type'] == 'create')
            <h1>New article</h1>
            @endif
            @if ($vars['form_type'] == 'edit')
            <h1>Edit article</h1>
            @if ($vars['revisions']->count())
                <?php $count = $vars['revisions']->count(); ?>

                <div class="form-group row">
                    <label for="revision" class="col-2 offset-8 col-form-label text-right">Revisions</label>
                    <div class="col-2">
                        <select name="revision" id="revision" class="form-control" onChange="event.preventDefault(); document.getElementsByTagName('body')[0].style.cursor = 'wait'; var url = '{{ route('backend.articles.article.edit' , ['id' => ':id' ]) }}'; window.location = url.replace(':id',this.value);">
                        @foreach($vars['revisions'] as $post)
                        <option value="{{ $post->id }}" {{ $vars['article']->id === $post->id ? 'selected' : '' }}>Rev {{ $count }}{{ $post->id == $vars['article']->id ? ' (current)' : '' }}{{ (int)$post->enabled === 1 ? ' (published)' : '' }}</option>
                        <?php $count--; ?>
                        @endforeach
                        </select>
                    </div>
                </div>

                @endif
            @endif

            @include('cscms::backend.partials.errors')

            <form method="post" action="{{ $vars['action'] }}">
                {!! csrf_field() !!}
                <input type="hidden" name="parent_id" value="{{ $vars['parent_id'] }}" />

                <div class="form-group row">
                    <label for="title" class="col-2 col-form-label">Name</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('title',$vars['article']->title) }}" id="title" name="title">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="slug" class="col-2 col-form-label">Slug</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('slug',$vars['article']->slug) }}" id="slug" name="slug">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="article_type_id" class="col-2 col-form-label">Type</label>
                    <div class="col-10">
                        <select name="article_type_id" id="article_type_id" class="form-control">
                            @if($vars['article_types']->count())
                                @foreach($vars['article_types'] as $item)
                                    @if ($item->id == $vars['article']->article_type_id)
                                    <option selected value="{{ $item->id }}">{{ $item->name }}</option>
                                    @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="publish_at" class="col-2 col-form-label">Publish at</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('publish_at',$vars['article']->publish_at) }}" id="publish_at" name="publish_at">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sort_order" class="col-2 col-form-label">Sort order</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('sort_order',$vars['article']->sort_order) }}" id="sort_order" name="sort_order">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="meta_description" class="col-2 col-form-label">Meta description</label>
                    <div class="col-10">
                        <textarea class="form-control" rows="5" id="meta_description" name="meta_description">{{ old('meta_description',$vars['article']->meta_description) }}</textarea>
                    </div>
                </div>

                @if ($vars['languages']->count())

                <div class="form-group row">

                    <label for="description" class="col-2 col-form-label">Description</label>
                    <div class="col-10">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($vars['languages'] as $item)
                                @if ( $loop->iteration == 1)
                                <li class="nav-item active">
                                @else
                                <li class="nav-item">
                                @endif
                                    <a class="nav-link" data-toggle="tab" href="#description-{{ $item->id }}" role="tab">{{ $item->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

               <div class="form-group row">

                    <div class="col-10 offset-sm-2">
                        <div class="tab-content">
                            @foreach($vars['languages'] as $item)
                                @if ( $loop->iteration == 1)
                                <div class="tab-pane active" id="description-{{ $item->id }}" role="tabpanel">
                                @else
                                <div class="tab-pane" id="description-{{ $item->id }}" role="tabpanel">
                                @endif
                                    <textarea name="description[{{ $item->id }}]" class="form-control editor" rows="10" placeholder="{{ $item->name }} description">{{ old('description['.$item->id.']',$vars['article']->descriptions()->where('language_id',$item->id)->pluck('content')->first()) }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                @endif

                <div class="form-group row">
                    <label for="enabled" class="col-2">Enabled</label>
                    <div class="col-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="enabled" value="1" {{ old('enabled',$vars['article']->enabled) === 1 ? 'checked' : '' }}> Yes
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@section('footer')
<script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace( 'editor' );
</script>
@endsection