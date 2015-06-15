@extends('admin.template')

@section('title')
Eliminar Usuario
@endsection

@section('content')
<div class="table-responsive panel form-delete">
	<div>¿Deseas eliminar el usuario <span>"{!! $data !!}"</span>?</div>
	{!! Form::open() !!}
	{!! Form::token() !!}
	<a href="{!! URL::to('admin/users') !!}" class="btn btn-info">Cancelar</a>
	<button type="submit" class="btn btn-danger">
         <span class="glyphicon glyphicon-remove"></span> Eliminar
	</button>
	{!! Form::close() !!}
<div>
@endsection

