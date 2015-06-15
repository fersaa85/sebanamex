@extends('admin.table')

@section('title')
Listado de cupones
@endsection

@section('data-table')

<?PHP $x=0; ?>
<?PHP $order_url = preg_replace('/(?:&|(\?))' . "order" . '=[^&]*(?(1)&|)?/i', '$1', $_SERVER['QUERY_STRING']); ?>

<table class="table table-condensed table-striped table-hover" id="list-table">
	<thead>
		<tr>
			<th></th>
			<th><a href="?{!! $order_url !!}&order=title|{!! $torder !!}">Título</a></th>
			<th><a href="?{!! $order_url !!}&order=categories.name|{!! $torder !!}">Categoria</a></th>
			<th><a href="?{!! $order_url !!}&order=disabled|{!! $torder !!}">Activo</a></th>
			<th class="action"></th>
			<th class="action"></th>
			<th class="action"></th>
		</tr>
	</thead>
	<tbody>
	@foreach ($rows as $row)
		<tr class="{!! $x++%2==0?'odd':'even'!!} @if ( $row->disabled == 1 ) disabled @endif">
			<td>{!! $row->thumb(50, 50) !!}</td>
			<td>{!! $row->title !!}</td>
			<td>{!! $row->category !!}</td>
			<td class="short-column"> 
				@if ($row->disabled == 0)
				Si @else No @endif
			</td>
			<td class="action"><a href='{!! URL::to('admin/coupons/profile/'.$row->id) !!}' class='btn btn-info'><span class="glyphicon glyphicon-info-sign"></span> Perfil</a></td>
			<td class="action"><a href='{!! URL::to('admin/coupons/edit/'.$row->id) !!}' class='btn btn-success'><span class="glyphicon glyphicon-pencil"></span> Editar</a></td>
			<td class="action"><a href='{!! URL::to('admin/coupons/delete/'.$row->id) !!}' class='btn btn-danger'><span class="glyphicon glyphicon-remove"></span> Eliminar</a></td>
		</tr>
    @endforeach			
	</tbody>	
</table>

@endsection