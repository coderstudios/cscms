@extends('cscms::backend.layouts.master')

@section('page_title')
Image
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col content-container">

            @if ($vars['form_type'] == 'create')
            <h1>New image</h1>
            @endif
            @if ($vars['form_type'] == 'edit')
            <h1>Edit image</h1>
            @endif

            @include('cscms::backend.partials.errors')

            <div id="uploader">

                <form action="{{ $vars['action'] }}" method="POST" class="dropzone" id="myAwesomeDropzone" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="folder" value="{{ $vars['id'] or '' }}" />
                    <div class="fallback">
                        <input name="file" type="file" multiple />
                    </div>
                </form>

            </div>

        </div>

    </div>

</div>

@endsection

@section('footer')
<script type="text/javascript">
    Dropzone.options.myAwesomeDropzone = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2000, // MB
        uploadMultiple: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        success:function(result, response) {
            document.location = response.path;
        },
    };
</script>
@endsection