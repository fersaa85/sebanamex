@layout('admin.template')

@section('title')
Agregar Usuario
@endsection

@section('content')
<?PHP 
	$class_text = array('class'=>'form-text');
	$class_textarea = array('class'=>'form-textarea');
	$class_select = array('class'=>'form-select'); 
?>
<?= Form::open_for_files() ?>
<?= Form::token() ?>
<table class="form">
	<thead>
		<tr>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="label"><label><?=Form::label('email', 'Usuario')?>: </label></td>
			<td>
				<?=Form::text('email', Input::old('email'), $class_text)?>
				@if($errors->has('email'))
				<div class="error">El nombre de usuario debe contar entre 5 a 12 caracteres.</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label"><label><?=Form::label('password', 'Contraseña')?>: </label></td>
			<td>
				<?=Form::password('password', $class_text)?>
				@if($errors->has('password'))
				<div class="error">La contraseña debe contener al menos 6 caracteres</div>
				
				@endif
			</td>
		</tr>
		<tr>
			<td class="label"><label><?=Form::label('cpassword', 'Confirmar contraseña')?>: </label></td>
			<td>
				<?=Form::password('cpassword', $class_text)?>
				@if($errors->has('cpassword'))
				<div class="error">Los campos de contraseña no concuerdan.</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label"><label><?=Form::label('name', 'Nombre')?>: </label></td>
			<td><?=Form::text('name', Input::old('name'), $class_text)?></td>
		</tr>
		<tr>
			<td class="label"><label><?=Form::label('surename', 'Apellidos')?>: </label></td>
			<td><?=Form::text('surename', Input::old('surename'), $class_text)?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td class="submit"><input type="submit" class="form-submit" value="Enviar" /></td>
		</tr>
	</tfoot>
</table>
<?=Form::close()?>
@endsection