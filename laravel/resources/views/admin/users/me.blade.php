@extends('admin.form')

@section('title')
Editar perfil
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
			<td class="label">{!! Form::label('email', 'Usuario') !!}: </td>
			<td>
				{!! Form::text('email', Input::old('email', $user->email), array('class'=>'form-control', 'disabled'=>'disabled')) !!}
			</td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('password', 'Contraseña') !!}: </td>
			<td>
				{!! Form::password('password', array('class'=>'form-control')) !!}
				@if($errors->has('password'))
				<div class="error">La contraseña debe contener al menos 6 caracteres</div>
				
				@endif
			</td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('cpassword', 'Confirmar contraseña') !!}: </td>
			<td>
				{!! Form::password('cpassword', array('class'=>'form-control')) !!}
				@if($errors->has('cpassword'))
				<div class="error">Los campos de contraseña no concuerdan.</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('name', 'Nombre') !!}: </td>
			<td>
				{!! Form::text('name', Input::old('name', $user->info->name), array('class'=>'form-control')) !!}
			</td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('surename', 'Apellidos') !!}: </td>
			<td>
				{!! Form::text('surename', Input::old('surename', $user->info->surename), array('class'=>'form-control')) !!}
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td class="submit">
				<button type="submit" class="btn btn-info">
               		 <span class="glyphicon glyphicon-saved"></span> Guardar
				</button>
			</td>
		</tr>
	</tfoot>
</table>
{!! Form::close() !!}
@endsection

