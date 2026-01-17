@extends('layouts.app')

@section('content')
<div class="container py-5">
	<div class="row justify-content-center">
		<div class="col-md-8 text-center">
			<h1 class="display-4 mb-3">404</h1>
			<p class="lead mb-4">The page you are looking for could not be found.</p>
			<a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
		</div>
	</div>
</div>
@endsection


