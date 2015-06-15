@extends('admin.template')

@section('title')
{!! $data->username !!}
@endsection

@section('content')

<div id="coupons-column-left">
	<div id="coupons-table" class="tabs-slider">
		<table class="form">
			<thead><tr><th></th><th></th></tr></thead>
			<tbody>
				<tr>
					<td class="label"><label>Nombre: </label></td>
					<td>{!! $data->info->name !!} {!! $data->info->surename !!}</td>
				</tr>
				<tr>
					<td class="label"><label>Correo: </label></td>
					<td>{!! $data->email !!}</td>
				</tr>
				<tr>
					<td class="label"><label>Estadisticas: </label></td>
					<td>
						{!! $data->scans()->count() !!} escaneos<br/>
						{!! $data->rankings()->count() !!} estrellas<br/>
						{!! count($comments) !!} comentarios<br/>
					</td>
				</tr>
				<tr>
					<td class="label"><label>Devices: </label></td>
					<td>{!! $data->tokens()->count() !!} equipos</td>
				</tr>
				<tr>
					<td class="label"><label>Registro: </label></td>
					<td>{!! $data->created_at !!}</td>
				</tr>
			</tbody>	
		</table>
	</div>
</div>

<div id="coupons-column-right">
	<div id="coupon-comments">
		<h3>Comentarios</h3>
		<ul class="coupon-comments">
			@if ( count($comments) != 0 )
			 
			@foreach ($comments as $comment)
			<li>
				<span><a href="{!! URL::to('admin/coupons/profile/'.$comment->id) !!}">{!! $comment->title !!}</a></span>
				<blockquote>{!! $comment->comment !!}</blockquote>
				<span>{!! $comment->created_at !!}</span>
			</li>
			@endforeach
			
			@else
				<li><blockquote>No hay comentarios</blockquote></li>
			@endif
		</ul>
	</div>
</div>

@endsection

