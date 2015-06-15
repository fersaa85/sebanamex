@extends('admin.template')

@section('title')
Eliminar cupón
@endsection

@section('content')
<div class="table-responsive panel form-delete"> 
	<div>¿Deseas eliminar el cupón <span>"{!! $data !!}"</span>?</div>
	{!! Form::open() !!}
	<a href="{!! URL::to('admin/coupons') !!}" class="btn btn-info">Cancelar</a>
	<button type="submit" class="btn btn-danger">
         <span class="glyphicon glyphicon-remove"></span> Eliminar
	</button>
	{!! Form::close() !!}
	
</div>
@endsection

