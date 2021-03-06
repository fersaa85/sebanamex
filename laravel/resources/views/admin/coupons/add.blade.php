@extends('admin.form')

@section('title')
Agregar cupón
@endsection

@section('data-form')

{!! Form::open(array('files' => true)) !!} 
<table class="table form">
	<thead>
		<tr>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="label">{!! Form::label('title', 'Título') !!} : </td>
			<td>
				{!! Form::text('title', Input::old('title'), array('class'=>'form-control')) !!} 
				@if($errors->has('title'))
				<div class="error">El título de la promoción debe contar entre 10 a 100 caracteres.</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('description', 'Descripción') !!} : </td>
			<td>{!! Form::textarea('description', Input::old('description'), array('class'=>'form-control')) !!} </td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('restriction', 'Restricciones') !!} : </td>
			<td>{!! Form::textarea('restriction', Input::old('restriction'), array('class'=>'form-control')) !!} </td>
		</tr>		
		<tr>
			<td class="label">{!! Form::label('category', 'Categoria') !!} :</td>
			<td>
				{!! Form::select('category', $categories, Input::old('category'), array('class'=>'form-control')) !!} 
			</td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('image', 'Imagen') !!} : </td>
			<td>
				{!! Form::file('image', array('class'=>'form-control')); !!} 
				<div class="description">Medidas sugeridas: 640x387</div>
				@if($errors->has('image'))
				<div class="error">Seleccione una imagen.</div>
				@endif
			</td>
		</tr>
		<tr>
			<td class="label">{!! Form::label('geolocation', 'Ubicación') !!} : </td>
			<td class="location">
				<script type="text/javascript">
			
					var map;
					var markers;
					var infoWindow;
					var markerImage;
					var lat;
					var lng;
					jQuery("input[name=expires]").datepicker({ changeMonth: true, changeYear: true, dateFormat:"yy-mm-dd" });
					jQuery(document).ready(function(){
					
						lat = jQuery("input[name=latitude]").val();
						lng = jQuery("input[name=longitude]").val();
						lat = !lat ? 19.432874 : lat;
						lng = !lng ? -99.133168 : lng;
						
						jQuery("input[name=latitude]").val(lat);
						jQuery("input[name=longitude]").val(lng);
						
						$("#geolocation").geocomplete({
							map: "#map-controls",
							details: "form",
							types: ["geocode", "establishment"],
							location: [lat, lng],
							markerOptions: {
								draggable: true
							},
							details: ".location",
							detailsAttribute: "data-geo"
						});
						
						$("#geolocation").bind("geocode:dragged", function(event, latLng){
							$("input[name=latitude]").val(latLng.lat());
							$("input[name=longitude]").val(latLng.lng());
		  				});
						
					})
			
				</script>
				<div id="map-controls"></div>
				{!! Form::text('geolocation', '', array('id'=>'geolocation', 'class'=>'form-control', 'placeholder'=>'Escribe una dirección')) !!} 
				{!! Form::hidden('latitude', Input::old('latitude'), array('data-geo'=>'lat')); !!} 
				{!! Form::hidden('longitude', Input::old('longitude'), array('data-geo'=>'lng')); !!} 
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