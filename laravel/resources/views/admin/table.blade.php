@extends('admin.template')

@section('content')

@yield('header-table')

@if (Session::get('message'))
	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<ul class="status"><li>{!! Session::get('message') !!}</li></ul>
	</div>
@endif

{!! Form::open(array("method"=>'GET', 'class'=>'form-inline', 'id'=>'form-controls')) !!}
<div id="controls-display" class="form-group">
	{!! App\Helpers\Pagination::limit($limit) !!}
</div>
<div id="controls-search" class="form-group">
	{!! App\Helpers\Pagination::search($search) !!}
</div>
{!! Form::close() !!}

<div class="panel"> 
	@yield('data-table') 
</div>

<div id="controls-show">{!! App\Helpers\Pagination::show(($page) * $limit + 1, $show, $total) !!}</div>
<div id="controls-pagination">{!! App\Helpers\Pagination::links($page, $total, $limit) !!}</div>

@endsection