@extends('admin.template')

@section('title')
Remover cupón premium
@endsection

@section('content')
<div class="table-responsive panel form-delete"> 
	<div>¿Deseas remover el cupón <span>"{!! $data !!}"</span> de la lista Premium?</div>
	{!! Form::open() !!}
	<a href="{!! URL::to('admin/coupons/premium') !!}" class="btn btn-info">Cancelar</a>
	<button type="submit" class="btn btn-danger">
         <span class="glyphicon glyphicon-remove"></span> Eliminar
	</button>
	{!! Form::close() !!}
	
</div>
@endsection

