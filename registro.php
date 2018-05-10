<?php

	require_once('db.php');
	
	if($_SERVER["SERVER_NAME"]=="localhost" || $_SERVER["SERVER_NAME"]=="127.0.0.1" ){
		if(isset($_POST["alta"])){
			if(!DB::comprobarNick($_POST["nickReg"])){
				if(!preg_match("/([a-zA-Z0-9])+([a-zA-Z0-9\._-])/",$_POST["nickReg"])){
						$errorNick= "El nick contiene caracteres no válidos";
				}else{
					if(strlen($_POST["nickReg"])<6){
						$errorPass= "La contraseña debe contener 6 caracteres mínimo";
					}else{
						if($_POST["passReg"]!=$_POST["passRep"]){
							$errorRpass="Las contraseñas no coinciden";
						}else{
							if($_POST["rol"]=="-1"){
								$errorRol="No ha seleccionado el tipo de usuario";
							}else{
								if($_POST["idEstacion"]=="-1"){
									$errorEstacion="No ha seleccionado ninguna estacion";
								}else{
									DB::creaUsuario($_POST["nickReg"],md5($_POST["nickReg"]),$_POST["rol"],$_POST["idEstacion"]);
									header("Location: index.php?accion=perfil");
									die();
								}
							}
						}					
					}
				}
			}else if(!preg_match("/([a-zA-Z0-9])+([a-zA-Z0-9\._-])/",$_POST["nickReg"])){
				$errorNick= "El nick contiene caracteres no válidos";
			}else{
				$errorNick= "El nick ya existe en nuestra base de datos";
			}
		}
	}else{
		echo "<h1>Solicitud no permitida!</h1>";
		exit();		
	}
?>
<section id="contenido">
	<h2 id="registro">Registro de usuario</h2>
	<div id="ventajas">
		<h3>Ventajas de ser usuario registrado</h3>
			<ul>
				<li>Accede rapidamente a los datos detallados de tu estación más cercana</li>
				<li>Guarda el registro de temperatura con un solo click.</li>
				<li>Lista de manera rápida un histórico de tus temperaturas</li>
			<ul>
	</div>
	<div id="formReg">
		<form action="index.php?accion=registrarse&peticion=nueva" method="post" autocomplete="off">
			<fieldset>
				<legend>Datos de Usuario</legend>
					<span id="eCampos"></span>
					<label for="nickReg">Nick</label>
					<input name="nickReg" id="nickReg" type="text" maxlength="16" required/>
					<span id="eNick" class="error"><?php if(isset($errorNick)){print $errorNick;}?></span>
					<label for="passReg">Contraseña</label>
					<input name="passReg" id="passReg" type="password" required/>
					<span id="ePass" class="error"><?php if(isset($errorPass)){print $errorPass;}?></span>
					<label for="passRep">Confirmacion de contraseña</label>
					<input name="passRep" id="passRep" type="password" required/>
					<span id="eRpass" class="error"><?php if(isset($errorRpass)){print $errorRpass;}?></span>
					<label for="rol">Tipo de usuario:</label>
					<select id="rol" name="rol">
						<option value="-1" selected="selected">Seleccione su tipo de usuario</option>
						<option value="guest" >Visitante</option>
						<option value="usuario" >Usuario</option>
						<option value="administrador">Administrador</option>
					</select>
					<span id="eRol" class="error"><?php if(isset($errorRol)){print $errorRol;}?></span>
					<label for="idEstacion">Seleccione su Estacion mas cercana</label>
					<select id="selectEstacion" name="idEstacion" >
						<?php 
							crearSelectEstaciones($datosEstaciones,$idSeleccionado); 
						?>
					</select>
					<span id="eEstacion" class="error"><?php if(isset($errorEstacion)){print $errorEstacion;}?></span>
					<input type="submit" name="alta" id="registrame" value="Registrame!"/>
					</fieldset>
		</form>
	</div>
	
</section>