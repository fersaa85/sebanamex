@extends('admin.template')

@section('title')
{{ $data->title }} <small>[{{ $data->category->name }}]</small>
@endsection

@section('content')


<div id="coupons-line-charts" class="panel form-inline panel-charts">
	<div id="controls-period" class="form-group">
		<label for="search">Período: <input type="text" name="search" class="form-control datepicker" value="{{ date("m/Y") }}" id="search" /></label>
	</div>
	<div id="chart-periods"></div>
</div>

<div id="coupons-column-left" class="panel">
	<a href="{{ URL::to($data->image) }}" class="modal-image">
		<img src="{{ URL::to($data->thumb(600,200)) }}" alt="{{ $data->title }}" class="responsive"/>
	</a>
	<div id="coupons-table" class="responsive-table">
		<table class="form table">
			<thead><tr><th></th><th></th></tr></thead>
			<tbody>
				<tr>
					<td class="label"><label>Estadisticas: </label></td>
					<td>
						{{ $data->scans->count() }} escaneos<br/>
						{{ round ($data->ratings()->avg("value")) }} estrellas<br/>
						{{ $data->comments->count() }} comentarios<br/>
					</td>
				</tr>
				<tr>
					<td class="label"><label>QR: </label></td>
					<td>
						<a href="{{ URL::to('admin/coupons/qr/'.$data->id.'/400') }}">Descargar</a> / 
						<a href="{{ URL::to($data->qr(400)) }}" class="modal-image">Visualizar</a>
					</td>
				</tr>
				<tr>
					<td class="label"><label>Descripción: </label></td>
					<td>{{ $data->description }}</td>
				</tr>
				<tr>
					<td class="label"><label>Restricciones: </label></td>
					<td>{{ $data->restrictions }}</td>
				</tr>
			</tbody>	
		</table>
	</div>
</div>

<div id="coupons-column-right" class="panel">
	<div id="map-coupon"></div>
	<div id="coupon-comments">
		<h3>Comentarios</h3>
		<ul class="coupon-comments">
			@if ( count($data->comments) != 0 )
			 
			@foreach ($data->comments as $comment)
			<li>
				<blockquote>{{ $comment->comment }}</blockquote>
				<span>{{ $comment->username }}, {{ $comment->created_at }}</span>
			</li>
			@endforeach
			
			@else
				<li><blockquote>No hay comentarios</blockquote></li>
			@endif
		</ul>
	</div>
</div>

<script type="text/javascript">
	
	/* CHARTS */
	var lineChart;
	var months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec"];
	var id = {{ $data->id }};
	
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
    
    function drawChart() {
        lineChart = new google.visualization.LineChart(jQuery("#chart-periods").get(0));
        jQuery.ajax("{{ URL::to('admin/coupons/scans-by-period') }}", {type:"POST", data:{id:[id]}}).done(drawLines);
	}
	
	function drawLines( $data) {
					
		jQuery('#coupons-line-charts').loader('hide');			
					
		var date   = $data.period.split("-");
		var period = new Date(date[0], parseInt(date[1]-1), parseInt(date[2]));
		var i 	   = 0;
		var l 	   = period.daysInMonth();
		var data   = [jQuery.parseJSON('{{ json_encode(array("Fechas", $data->title)) }}')];
						
		while (i < l) {
			var v = 0;
			var s = period.yyyymmdd();
			if ($data.data[id]) {
				if ($data.data[id][s]) v = parseInt($data.data[id][s]);
			}
			data.push([ months[period.getMonth()] + " " + period.getDate(), v]);
			period.setDate(period.getDate()+1);
			i++;
		}
										
		var dataChart = google.visualization.arrayToDataTable(data);
		var options = { title: 'Escaneos por día', legend: 'none',  vAxis:{viewWindow: {min: 0}}, chartArea:{left:50,top:40,width:"90%"}, fontSize:14 };
				
        lineChart.draw(dataChart, options);
		
	}	
	
	
	/* DATEPICKER */	
	jQuery(".datepicker").monthpicker({monthNames: months});
	jQuery(".datepicker").change(function(){
		var o = {id:[id], period:jQuery(this).val()};
		jQuery('#coupons-line-charts').loader('show', {overlay:false});
		jQuery.ajax("{{ URL::to('admin/coupons/scans-by-period') }}", {type:"POST", data:o}).done(drawLines);
	})
		
	
	
	/* MAPS */				
	var defaultLocation = new google.maps.LatLng({{ $data->latitude }}, {{ $data->longitude }});		
	var mapOptions = {zoom: 16, mapTypeId: google.maps.MapTypeId.ROADMAP, streetViewControl:true};
	var map = new google.maps.Map(document.getElementById("map-coupon"), mapOptions);
	map.setCenter(defaultLocation);
	var marker = new google.maps.Marker({draggable: false, position: defaultLocation, map: map});
						
						
	
	/* PROTOTYPE DATE */
	Date.prototype.yyyymmdd = function() {         
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
   }; 
   
   Date.prototype.daysInMonth = function(){
    	var d= new Date(this.getFullYear(), this.getMonth()+1, 0);
    	return d.getDate();
   }
      
</script>

@endsection

