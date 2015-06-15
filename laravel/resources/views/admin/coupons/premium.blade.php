@extends('admin.table')

@section('title')
Listado de cupones premium
@endsection

<?PHP $x=0; ?>
<?PHP $order_url = preg_replace('/(?:&|(\?))' . "order" . '=[^&]*(?(1)&|)?/i', '$1', $_SERVER['QUERY_STRING']); ?>

@section('header-table')

<div class="table-responsive panel"> 
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
			<td class="label">{!! Form::label('coupon', 'Cupón') !!}: </td>
			<td>
				{!! Form::select('coupon', $list, 0, array('class'=>'form-control')) !!}
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td class="submit">
				<button type="submit" class="btn btn-info">
               		 <span class="glyphicon glyphicon-plus"></span> Agregar
				</button>
			</td>
		</tr>
	</tfoot>
</table>
{!! Form::close() !!}
</div>

@endsection

@section('data-table')

<table class="table table-condensed table-striped table-hover" id="list-table">
	<thead>
		<tr>
			<th><a href="?{{ $order_url }}&order=title|{{ $torder }}">Título</a></th>
			<th><a href="?{{ $order_url }}&order=company|{{ $torder }}">Cliente</a></th>
			<th><a href="?{{ $order_url }}&order=categories.name|{{ $torder }}">Categoria</a></th>
			<th class="action"></th>
			<th class="action"></th>
		</tr>
	</thead>
	<tbody>
	@foreach ($rows as $row)
		<tr class="{{ $x++%2==0?'odd':'even' }} @if ( $row->disabled == 1 ) disabled @endif">
			<td>{{ $row->title }}</td>
			<td>{{ $row->category }}</td>
			<td class="action"><a href='{{URL::to('admin/coupons/profile/'.$row->id)}}' class='btn btn-info'><span class="glyphicon glyphicon-info-sign"></span> Perfil</a></td>
			<td class="action"><a href='{{URL::to('admin/coupons/delete-premium/'.$row->id)}}' class='btn btn-danger'><span class="glyphicon glyphicon-remove"></span> Eliminar</a></td>
		</tr>
    @endforeach			
	</tbody>	
</table>

@endsection