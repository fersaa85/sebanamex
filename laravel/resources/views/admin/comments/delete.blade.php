@extends('admin.template')

@section('title')
Eliminar comentario
@endsection

@section('content')
<div class="table-responsive panel form-delete"> 
	<div>¿Deseas eliminar el comentario <span>"{!! $data !!}"</span>?</div>
	{!! Form::open() !!}
	<a href="{!! URL::to('admin/comments') !!}" class="btn btn-info">Cancelar</a>
	<button type="submit" class="btn btn-danger">
         <span class="glyphicon glyphicon-remove"></span> Eliminar
	</button>
	{!! Form::close() !!}
	
</div>
@endsection

