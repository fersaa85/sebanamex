@extends('admin.form')

@section('title')
Editar Usuario
@endsection

@section('content')

{!! Form::open() !!}

<table class="table form">
	<thead>
		<tr>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="label"><label>{!! Form::label('email', 'Correo') !!}: </label></td>
			<td>
				{!! Form::text('email', Input::old('email', $data->email), array('class'=>'form-text')) !!}
			</td>
		</tr>
		<tr>
			<td class="label"><label>{!! Form::label('password', 'Contraseña') !!}: </label></td>
			<td>
				{!! Form::password('password', array('class'=>'form-text')) !!}
				@if($errors->has('password'))
				<div class="error">La contraseña debe contener al menos 6 caracteres</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label"><label>{!! Form::label('cpassword', 'Confirmar contraseña') !!}: </label></td>
			<td>
				{!! Form::password('cpassword', array('class'=>'form-text')) !!}
				@if($errors->has('cpassword'))
				<div class="error">Los campos de contraseña no concuerdan.</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label"><label>{!! Form::label('name', 'Nombre') !!}: </label></td>
			<td>
				{!! Form::text('name', Input::old('name', $data->info->name), array('class'=>'form-text')) !!}
				@if($errors->has('cpassword'))
				<div class="error">Campo de nombre incorrecto.</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label"><label>{!! Form::label('surename', 'Apellidos') !!}: </label></td>
			<td>
				{!! Form::text('surename', Input::old('surename', $data->info->surename), array('class'=>'form-text')) !!}
				@if($errors->has('cpassword'))
				<div class="error">Campo de apellido incorrecto.</div>
				@endif
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td class="submit">
				<button type="submit" class="btn btn-info">
               		 <span class="glyphicon glyphicon-saved"></span> Enviar
				</button>
			</td>
		</tr>
	</tfoot>
</table>
{!! Form::close() !!}
@endsection

