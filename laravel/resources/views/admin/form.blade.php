@extends('admin.template')
@section('content')


@if (Session::get('message'))
	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<ul class="status"><li>{{ Session::get('message') }}</li></ul>
	</div>
@endif

@if (Session::get('error'))
	<div class="alert alert-error alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<ul class="status"><li>{{ Session::get('error') }}</li></ul>
	</div>
@endif
<!-- table-responsive panel -->
<div class=""> 
	@yield('data-form') 
</div>

@endsection