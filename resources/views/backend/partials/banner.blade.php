@if($success)

<div class="row">

	<div class="col">

		<div class="alert alert-success" role="alert">{!! $success !!}</div>

	</div>

</div>

@endif

@if($error)

<div class="row">

	<div class="col">

		<div class="alert alert-danger text-center" role="alert">{!! $error !!}</div>

	</div>

</div>

@endif

@if($csrf_error)

<div class="row">

	<div class="col">

		<div class="alert alert-success" role="alert">{!! $csrf_error !!}</div>

	</div>

</div>

@endif
