<!DOCTYPE HTML>
<html lang="en-GB">
<head>
    <meta charset="UTF-8" />
    <title>Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	
    {!! HTML::style('assets/css/bootstrap.min.css') !!}
    {!! HTML::style('assets/css/admin.css') !!}
    
    {!! HTML::script('assets/js/jquery.min.js') !!}
    {!! HTML::script('assets/js/bootstrap.min.js') !!}
    
</head>
<body>
	<div id="admin-login">
		<div class="content panel login">			
			<?php
				//print_r(Session::all());	
			?>
			
			{!! Form::open() !!}
				<p>
					{!! Form::label('username', 'Usuario') !!}
					{!! Form::text('username', Input::old('username'), array("class"=>"form-control")) !!}
				</p>
				<p>
					{!! Form::label('password', 'Contraseña') !!}
					{!! Form::password('password', array("class"=>"form-control")) !!}
				</p>
				@if (Session::has('login_errors'))
					<div class="error">Usuario o contraseña incorrecta.</div>
				@endif
				<p class="form-action">
					<button type="submit" class="btn btn-info">
               			<span class="glyphicon glyphicon-record"></span> Ingresar
			   		</button>
				</p>
			{!! Form::close() !!}
		</div>
	</div>
	<script>
		$(document).ready(function(){
			window.scrollTo(0,1);
		})
	</script>
</body>
</html>