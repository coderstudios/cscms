@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <h3>Please review:</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif