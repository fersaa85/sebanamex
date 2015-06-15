@extends('admin.table')

@section('title')
Listado de notificaciones
@endsection

<?PHP $x=0; ?>
<?PHP $order_url = preg_replace('/(?:&|(\?))' . "order" . '=[^&]*(?(1)&|)?/i', '$1', $_SERVER['QUERY_STRING']); ?>

@section('header-table')

<div class="table-responsive panel"> 
{!! Form::open() !!}
<table class="form table">
	<thead>
		<tr>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="label">{!! Form::label('message', 'Mensaje') !!}: </td>
			<td>
				{!! Form::textarea('message', Input::old('message'), array('class'=>'form-control')) !!}
				<div class="text-counter">90 caracteres restantes</div>
				@if($errors->has('message'))
				<div class="error">El mensaje debe ser de entre 10 y 90 caracteres.</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('coupon', 'Cupón') !!}: </td>
			<td>
				{!! Form::select('coupon', $coupons, 0, array('class'=>'form-control')) !!}
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

<script type="text/javascript">

	jQuery("textarea[name=message]").keypress(function(){
		var left = 90 - jQuery(this).val().length;
		var text = jQuery(this).parent().find('div.text-counter');
		if (left <= 0) text.attr("style", "color:red");
		else text.removeAttr("style");
		text.text( left + " caracteres restantes");
	})
	
</script>

<table class="table table-condensed table-striped table-hover" id="list-table">
	<thead>
		<tr>
			<th><a href="?{!! $order_url !!}&order=message|{!! $torder !!}">Mensaje</a></th>
			<th><a href="?{!! $order_url !!}&order=title|{!!   $torder !!}">Cupón</a></th>
			<th><a href="?{!! $order_url !!}&order=percent|{!! $torder !!}">Porcentaje</a></th>
			<th><a href="?{!! $order_url !!}&order=current|{!! $torder !!}">Enviados</a></th>
			<th class="action"></th>
			<th class="action"></th>
		</tr>
	</thead>
	<tbody>
	@foreach ($rows as $row)
		<tr class="{!! $x++%2==0?'odd':'even' !!}">
			<td>{!! $row->message !!}</td>
			<td>{!! $row->title==""? 'N/A' : $row->title !!}</td>
			<td>{!! $row->finished ? 100   : round($row->current/$devices)*100 !!}%</td>
			<td>{!! $row->current !!}</td>
			<td class="action">
				@if ( $row->finished == 0 )
				<a href='{!!URL::to('admin/notifications/delete/'.$row->id)!!}' class='btn btn-danger'>Eliminar</a>				
				@endif
			</td>
			<td class="action">
				@if ( $row->finished == 0 )
				<a class='btn btn-success'>{!! $row->current==0?'Iniciar':'Continuar' !!}</a>
				@else
				<a class='btn btn-success'>Finalizado</a>
				@endif
			</td>
		</tr>
    @endforeach			
	</tbody>	
</table>

@endsection