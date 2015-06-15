@extends('admin.table')

@section('title')
Listado de usuarios
@endsection

@section('data-table')

<?PHP $x=0; ?>
<?PHP $order_url = preg_replace('/(?:&|(\?))' . "order" . '=[^&]*(?(1)&|)?/i', '$1', $_SERVER['QUERY_STRING']); ?>

<table class="table table-condensed table-striped table-hover" id="list-table">
	<thead>
		<tr>
			<th>Correo</th>
			<th>Nombre</th>
			<th>Nivel</th>
			<th class="action"></th>
			<th class="action"></th>
			<th class="action"></th>
		</tr>
	</thead>
	<tbody>
	@foreach ($rows as $row)
		<tr>
			<td>{!! $row->email !!}</td>
			<td>{!! $row->name." ".$row->surename  !!}</td>
			<td>{!! $row->level !!}</td>
			<td class="action"><a href='{!! URL::to('admin/users/profile/'.$row->id) !!}' class='btn btn-info'>Perfil</a></td>
			<td class="action"><a href='{!! URL::to('admin/users/edit/'.$row->id) !!}' class='btn btn-success'>Editar</a></td>
			<td class="action"><a href='{!! URL::to('admin/users/delete/'.$row->id) !!}' class='btn btn-danger'>Eliminar</a></td>
		</tr>
    @endforeach			
	</tbody>	
</table>

@endsection