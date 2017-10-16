@if($success_message)

<div class="row">

	<div class="col">

		<div class="alert alert-success" role="alert">{!! $success_message !!}</div>

	</div>

</div>

@endif

@if($error_message)

<div class="row">

	<div class="col">

		<div class="alert alert-danger text-center" role="alert">{!! $error_message !!}</div>

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
