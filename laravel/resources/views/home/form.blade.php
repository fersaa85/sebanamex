<div style="font-family: Helvetica; font-size: 0.8em;">
	
	
	
	<style type="text/css">
		label span {width: 100px; text-align: right; display: inline-block;}
		h3 {text-transform: uppercase; color: #333;}
		label {color: #555;}
		form {border: 1px solid #EEE; padding: 15px;}
		input[type=submit] {margin-left: 195px;}
		.form_set {border: 1px solid #CCC; padding: 15px; margin-bottom: 20px;}
		
	</style>

	
	<div class="form_set">
		<h2>LIBRES</h2>
		<form action="api/login" method="post" target="_blank">
			<h3>Login</h3>
			<label><span>SOID:</span> <input type="text" name="soid" /></label><br/>
			<label><span>Contraseña:</span> <input type="text" name="password" /></label><br/>
			<input type="submit" value="Enviar"/>
		</form>
		
		<hr/>
	
		<form action="api/register" method="post" target="_blank">
			<h3>Register</h3>
			<!-- 
			<label><span>SOID:</span> <input type="text" name="email" /></label><br/>
			-->
			<label><span>SOID:</span> <input type="text" name="soid" /></label><br/>
			<label><span>Correo:</span> <input type="text" name="email" /></label><br/>
			<label><span>Contraseña:</span> <input type="text" name="password" /></label><br/>
			<label><span>Nombre Completo:</span> <input type="text" name="full_name" /></label><br/>
			
			<label><span>Genero:</span> 
			<select name="genre" />
						<option value="man" >Hombre</option>
						<option value="woman" >Mujer</option>
			</select></label><br/>
			<label><span>Tipo de sangre:</span> <input type="text" name="blood_type" /></label><br/>
			<label><span>Hobbies:</span> <input type="text" name="hobbies" /></label><br/>
			
			
			<input type="hidden" name="medical_service" value="1" />
			<input type="submit" value="Enviar"/>
		</form>
		
		<hr/>
		
		<form action="api/coupons" method="get" target="_blank">
			<h3>Cupones</h3>
			<input type="submit" value="Enviar"/>
		</form>
			
	</div>
	
	
	<div class="form_set">
		<h2>RESTRINGIDOS</h2>
		
		<form action="api/logout" method="get" target="_blank">
			<h3>Logout</h3>
			<input type="submit" value="Enviar"/>
		</form>
				
		<hr/>
		
		<form action="api/profile" method="get" target="_blank">
			<h3>Perfil</h3>
			<input type="submit" value="Enviar"/>
		</form>
				
		<hr/>
	
		<form action="api/profile" method="post" target="_blank">
			<h3>Perfil (Actualizar)</h3>
			<label><span>Correo:</span> <input type="text" name="email" /></label><br/>
			<label><span>Contraseña:</span> <input type="text" name="password" /></label><br/>
			<label><span>Nombre Completo:</span> <input type="text" name="full_name" /></label><br/>
			
			<label><span>Genero:</span> 
			<select name="genre" />
						<option value="man" >Hombre</option>
						<option value="woman" >Mujer</option>
			</select></label><br/>
			<label><span>Tipo de sangre:</span> <input type="text" name="blood_type" /></label><br/>
			<label><span>Hobbies:</span> <input type="text" name="hobbies" /></label><br/>
			
			
			<input type="hidden" name="medical_service" value="1" />
			<input type="submit" value="Enviar"/>

		</form>
		
		<form action="api/comments" method="post" target="_blank">
			<h3>Comments</h3>
			<label><span>ID Cupón:</span> <input type="text" name="id" value="156" /></label><br/>
			<label><span>Comentario:</span> <input type="text" name="comment" /></label><br/>
			<label><span>Rating (1-5):</span> <input type="text" name="rating" /></label><br/>
			<input type="submit" value="Enviar"/>
		</form>

		<hr/>
	
		<form action="api/scan" method="post" target="_blank">
			<h3>Scans</h3>
			<label><span>QR:</span> <input type="text" name="qr" value="c30821822f957a2ea343e277197be9de" /></label><br/>
			<input type="submit" value="Enviar"/>
		</form>

		<hr/>
	
		<form action="api/rating" method="post" target="_blank">
			<h3>Rating</h3>
			<label><span>ID Cupón:</span> <input type="text" name="id" value="156" /></label><br/>
			<label><span>Rating (1-5):</span> <input type="text" name="rating" /></label><br/>
			<input type="submit" value="Enviar"/>
		</form>

		<hr/>
	
		<form action="api/notification" method="post" target="_blank">
			<h3>Notifications</h3>
			<label><span>Token:</span> <input type="text" name="token" value="" /></label><br/>
			<label><span>OS:</span> <input type="text" name="os" value="android"/></label><br/>
			<input type="submit" value="Enviar"/>
		</form>
		
		
		
		
		
		
		
		
		
		<form action="api/resetpassword" method="post" target="_blank">
			<h3>Recuperar contraseña</h3>
			<label><span>SOID:</span> <input type="text" name="soid" value="" /></label><br/>
			<input type="submit" value="Enviar"/>
		</form>
		
		
		
		<form action="api/reports" method="post" target="_blank">
			<h3>Reportes/incidencias</h3>
			<label><span>SOID:</span> <input type="text" name="soid" value="" /></label><br/>
			<label><span>Mensaje:</span> <input type="text" name="message" value="" /></label><br/>
			<label><span>Tipo:</span> 
			<select name="report_category_id">
				<option value="1">Problemas con el loguin</option>
				<option value="2">Sugerencias</option>
				<option value="3">Quejas</option>
			</select>
			<input type="submit" value="Enviar"/>
		</form>
	</div>

</div>