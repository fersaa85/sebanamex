@extends('admin.table')

@section('title')
Listado de comentarios
@endsection

@section('data-table')

<?PHP $x=0; ?>
<?PHP $order_url = preg_replace('/(?:&|(\?))' . "order" . '=[^&]*(?(1)&|)?/i', '$1', $_SERVER['QUERY_STRING']); ?>

<table class="table table-condensed table-striped table-hover" id="list-table">
	<thead>
		<tr>
			<th><a href="?{!! $order_url !!}&order=username|{!! $torder !!}">Usuario</a></th>
			<th><a href="?{!! $order_url !!}&order=comment|{!! $torder !!}">Comentario</a></th>
			<th><a href="?{!! $order_url !!}&order=title|{!! $torder !!}">Cupón</a></th>
			<th><a href="?{!! $order_url !!}&order=comments.created_at|{!! $torder !!}">Fecha</a></th>
			<th class="action"></th>
		</tr>
	</thead>
	<tbody>
	@foreach ($rows as $row)
		<tr class="{!! $x++%2==0?'odd':'even' !!} @if ( $row->disabled == 1 ) disabled @endif">
			<td>{!! $row->email !!}</td>
			<td>{!! $row->comment !!}</td>
			<td>{!! $row->title !!}</td>
			<td>{!! $row->created !!}</td>
			<td class="action"><a href='{!! URL::to('admin/comments/delete/'.$row->id) !!}' class='btn btn-danger'><span class="glyphicon glyphicon-remove"></span> Eliminar</a></td>
		</tr>
    @endforeach			
	</tbody>	
</table>

@endsection