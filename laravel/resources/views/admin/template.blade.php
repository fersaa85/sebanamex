<!DOCTYPE HTML>
<html lang="es-MX">
<head>
    <meta charset="UTF-8" />
    <title>
    	Banamex :: @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href='http://fonts.googleapis.com/css?family=Duru+Sans|Carrois+Gothic' rel='stylesheet' type='text/css' />
    
    {!!  HTML::style('assets/css/fancybox/jquery.fancybox.css')  !!}
    {!!  HTML::style('assets/css/fancybox/jquery.fancybox-buttons.css')  !!}
    {!!  HTML::style('assets/css/fancybox/jquery.fancybox-thumbs.css')  !!}
    {!!  HTML::style('assets/css/plugins/jquery-ui-1.10.1.custom.min.css')  !!}
    {!!  HTML::style('assets/css/plugins/jquery.chosen.css')  !!}
    {!!  HTML::style('assets/css/plugins/jquery.loader.min.css')  !!}
    {!!  HTML::style('assets/css/bootstrap.min.css')  !!}
    {!!  HTML::style('assets/css/admin.css')  !!}
        
   
    {!!  HTML::script('assets/js/jquery.min.js')  !!}
    {!!  HTML::script('assets/js/plugins/jquery.ui.min.js')  !!}
    {!!  HTML::script('assets/js/plugins/jquery.geocomplete.min.js')  !!}
    {!!  HTML::script('assets/js/plugins/jquery.fancybox.min.js')  !!}
    {!!  HTML::script('assets/js/plugins/jquery.fancybox.thumbs.js')  !!}
    {!!  HTML::script('assets/js/plugins/jquery.fancybox.buttons.js')  !!}
    {!!  HTML::script('assets/js/plugins/jquery.mtz.monthpicker.js')  !!}
    {!!  HTML::script('assets/js/plugins/jquery.chosen.min.js')  !!}
    {!!  HTML::script('assets/js/plugins/jquery.loader.min.js')  !!}
    {!!  HTML::script('assets/js/bootstrap.min.js')  !!}
    {!!  HTML::script('http://maps.google.com/maps/api/js?sensor=true&libraries=geometry')  !!}
    {!!  HTML::script('http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places')  !!}
    {!!  HTML::script('https://www.google.com/jsapi')  !!}
    {!!  HTML::script('assets/js/site.admin.js')  !!}
        
</head>
<body>
		
		<div id="header" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
								
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						@if (Auth::user()->is('Super Admin')) 
						<li><a href="{!! URL::to('admin/coupons') !!}"><span class="glyphicon glyphicon-tags"></span> &nbsp;Cupones</a>
							<ul>
								<li><a href="{!! URL::to('admin/coupons') !!}">Listado</a></li>
								<li><a href="{!! URL::to('admin/coupons/add') !!}">Agregar</a></li>
								<li><a href="{!! URL::to('admin/coupons/premium') !!}">Premium</a></li>
							</ul>
						</li>
						<li><a href="{!! URL::to('admin/users/') !!}"><span class="glyphicon glyphicon-user"></span> Usuarios</a>
							<ul>
								<li><a href="{!! URL::to('admin/users/') !!}">Listado</a></li>
								<li><a href="{!! URL::to('admin/users/add') !!}">Agregar</a></li>
								<li><a href="{!! URL::to('admin/comments') !!}">Comentarios</a></li>
								<li><a href="{!! URL::to('admin/notifications') !!}">Notificaciones</a></li>
							</ul>
						</li>
						@endif
						<li><a href="{!! URL::to('admin/users/me') !!}"><span class="glyphicon glyphicon-cog"></span> Perfil</a></li>
						<li><a href="{!! URL::to('logout') !!}" rel='ajax'><span class="glyphicon glyphicon-off"></span> Salir</a></li>
					</ul>
				</div>
				
			</div>
		</div>		
		
		<div id="content" class="container">
			<h1>
				@yield('title')
			</h1>
			@yield('content')
			<div style="clear:both;"></div>
		</div>

</body>
</html>