<?php
	//Funciones para ajax mediante Jquery
	
	if(isset($_POST["recogerPosicion"])){
		include('meteogalicia.php');
		$datosEstacion=recogerEstacion($_POST["recogerPosicion"]);
		$coordenadas=sacarCoordenadas($datosEstacion);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($coordenadas);
		
	}
	
	if(isset($_POST["recogerDatos"])){
		include('meteogalicia.php');
		$respuestas=sacarTodo();
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($respuestas);	
	}
	//include('meteogalicia.php');
	//Id seleccionado del select por defecto
	$idSeleccionado=-1;
	//Recogemos los datos del json
	$jsonMeteo=recogerJsonMeteo();
	$datosEstaciones=ordenarConcellos($jsonMeteo);
	//Recogemos el html de la pagina meteo
	$paginaMeteo=obtenerDatos("http://www.meteogalicia.gal/web/index.action");
	
	
	//Función para el link de registro
	function linkRegistro(){
		if(isset($_GET["idEstacion"])){
			return 'index.php?accion=registrarse&idEstacion='.$_GET["idEstacion"];
		}else{
			return 'index.php?accion=registrarse';
		}
	}
	
	//Función para crear el historico de las temperaturas cogidas de la base de dadtos
	function crearHistorico($temperaturasDB){
		print '<table sumary="Tabla que muestra los ultimos 10 registros de temperatura" id="historicoTemp">'."\n";
		print '					<caption>Historico de temperaturas</caption>'."\n";
		print '					<thead>'."\n";
		print '						<tr>'."\n";
		print '							<th>Nº</th>'."\n";
		print '							<th>ESTACIÓN METEOROLÓGICA</th>'."\n";
		print '							<th>TEMP.</th>'."\n";
		print '						</tr>'."\n";
		print'					</thead>'."\n";
		print'					<tbody>'."\n";
		
		for($i=0; $i<sizeof($temperaturasDB);$i++){
			print'						<tr>'."\n";
			print'							<td>'.($i+1).'</td>'."\n";
			if($i%2==0){$clase="par";}else{$clase="impar";};				
			print'							<td class="est"><a class="'.$clase.'"href="index.php?idEstacion='.$temperaturasDB[$i][0].'">';
			//Recogemos la estacion mediante el id
			$datosEstacion=recogerEstacion($temperaturasDB[$i][0]);
			print sacarEstacion($datosEstacion);
			print '</a></td>'."\n";	
			print'							<td>'.$temperaturasDB[$i][1].'º</td>'."\n";
			print'						</tr>'."\n";
		}
		print '						</tbody>'."\n";
		print '					</table>'."\n";
	}
	
	//Función para recortar del html los datos diarios y sacarlos por array
	function crearTempDiaria($paginaMeteo){
		$ciudades=["A Coruña", "Ferrol", "Lugo","Ourense","Pontevedra","Santiago de Compostela","Vigo"];
		$div=explode('<div id="temperaturasXeral" >',$paginaMeteo);
		$contenido=explode('<br clear="all"/>',$div[1]);
		$tabla=explode('</table>',$contenido[0]);
		$temp=explode('&nbsp;',$tabla[0]);
		// Recorremos los recortes hechos y alamacenamos en un array
		for($i=1; $i<sizeof($temp); $i++){
			
			$envio=explode('&deg;',$temp[$i]);
			$temperaturas[]=$envio[0];
			if($i%2==0){
				$todas[]=$temperaturas;
				unset($temperaturas);
			}
			unset($envio);	
		}
		//Mostramos el resultado
		print '<table sumary="Tabla que muestra los datos diarios de temperatura" id="tablaTemp">'."\n";
		print '		<caption>Temperaturas Mínimas y Máximas</caption>'."\n";
		print '		<thead>'."\n";
		print '			<tr>'."\n";
		print '				<th>CIUDAD</th>'."\n";
		print '				<th>MIN</th>'."\n";
		print '				<th>MAX</th>'."\n";
		print '			</tr>'."\n";
		print '		</thead>'."\n";
		print '		<tbody>'."\n";
			for($i=0; $i<=sizeof($ciudades)-1;$i++){
				print'			<tr class="fila">'."\n";
				print'				<td class="ciudad">'.$ciudades[$i].'</td>'."\n";	
				print'				<td class="min">'.$todas[$i][0].'</td>'."\n";
				print'				<td class="max">'.$todas[$i][1].'</td>'."\n";
				print'			</tr>'."\n";
			}
		print '				</tbody>'."\n";
		print '		</table>'."\n";
	}
	
	//Crea el panel de información
	function crearDatosEstacion($datosEstacion){
		
		$fecha=strtotime($datosEstacion["listEstadoActual"][0]["dataLocal"]);
		print '<div id="datosEstacion">'."\n";
		print '		<h2>'.$datosEstacion["listEstadoActual"][0]["estacion"].'</h2>'."\n";
		print '			<table sumary="Tabla que muestra los datos diarios de temperatura" id="diariosTemp" >'."\n";
		print '					<caption>Resumen de valores térmicos</caption>'."\n";
		print '					<tbody>'."\n";
		print '						<tr>'."\n";		
		print '							<th>Sensacion térmica</th>'."\n";	
		print '							<td>'.$datosEstacion["listEstadoActual"][0]["valorSensTermica"].'</td>'."\n";
		print '						</tr>'."\n";
		print '						<tr>'."\n";		
		print '							<th>Variación de temperatura</th>'."\n";	
		print '							<td ><img src="http://www.meteogalicia.gal/datosred/infoweb/meteo/imagenes/termometros/'.$datosEstacion["listEstadoActual"][0]["lnIconoTemperatura"].'.png"</td>'."\n";
		print '						</tr>'."\n";
		print '						<tr>'."\n";		
		print '							<th>Temperatura</th>'."\n";	
		print '							<td>'.$datosEstacion["listEstadoActual"][0]["valorTemperatura"].'</td>'."\n";
		print '						</tr>'."\n";
		print '					</tbody>'."\n";
		print '				</table>'."\n";
		print '			<table sumary="Tabla que muestra los datos diarios del cielo"  id="diariosCielo">'."\n";
		print '					<caption>Resumen de valores atmosféricos</caption>'."\n";
		print '						<tr>'."\n";		
		print '							<th>Estado del Cielo</th>'."\n";	
		print '							<th>Estado del Viento</th>'."\n";
		print '						</tr>'."\n";
		print '						<tr>'."\n";		
		print '							<td ><img src="http://www.meteogalicia.gal/datosred/infoweb/meteo/imagenes/meteorosmapa/ceo/'.$datosEstacion["listEstadoActual"][0]["lnIconoCeo"].'.png"</td>'."\n";	
		print '							<td ><img src="http://www.meteogalicia.gal/datosred/infoweb/meteo/imagenes/meteoros/vento/combo/'.$datosEstacion["listEstadoActual"][0]["lnIconoVento"].'.png"</td>'."\n";
		print '						</tr>'."\n";
		print '				</table>'."\n";
		print '			<table sumary="Tabla que muestra los datos de ubicación" id="ubicacion">'."\n";
		print '					<caption>Datos de interés de la estación</caption>'."\n";
		print '						<tr>'."\n";		
		print '							<th>Concello</th>'."\n";	
		print '							<th>Provincia</th>'."\n";
		print '							<th>Localización</th>'."\n";
		print '						</tr>'."\n";
		print '						<tr>'."\n";		
		print '							<td >'.nombrarConcellos($datosEstacion["listEstadoActual"][0]["concello"]).'</td>'."\n";	
		print '							<td >'.$datosEstacion["listEstadoActual"][0]["provincia"].'</td>'."\n";
		print '							<td >('.$datosEstacion["listEstadoActual"][0]["lat"].' , '.$datosEstacion["listEstadoActual"][0]["lon"].')</td>'."\n";		
		print '						</tr>'."\n";
		print '				</table>'."\n";
		print '				<h4><b>Datos obtenidos:</b> '.date("d/m/Y H:i",$fecha).'</h4>'."\n";
		print '		</div>'."\n";
		
	}
	
	//Crea el panel de información
	function crearInformacion(){
		print '<div id="informacion">'."\n";
		print '		<h3>Información de uso:</h3>'."\n";
		print '			<ul>'."\n";
		print '				<li>Para conocer datos más detallados de su concello, seleccionelo y haga click en Pedir datos.</li>'."\n";
		print '				<li>Para conocer datos instantaneos haga clic en un icono del mapa y le mostrará más datos.</li>'."\n";
		print '			</ul>'."\n";
		print '	</div>'."\n";	
	}
	
	//Funcion para nombrar concellos, poner la primera letra en mayuscula y las demás en minusucula
	function nombrarConcellos($concello){
		$palabrasConcello=explode(" ",$concello);
		$concelloFinal="";
		for($i=0; $i<=sizeof($palabrasConcello)-1;$i++){
			$concelloFinal.=$palabrasConcello[$i][0];
			if(strlen($palabrasConcello[$i])>1){
			$concelloFinal.=mb_strtolower(mb_substr($palabrasConcello[$i], 1, null, 'UTF-8'),'UTF-8');
			}
			if($i<sizeof($palabrasConcello)-1){
				$concelloFinal.=" ";
			}
		}	
		return $concelloFinal;
	}
	
	//Funcion para nombrar estaciones, para quitar la repeticion de palabras en coruña y santiago
	function nombrarEstacion($estacion){
		$estacionCorte=explode("-",$estacion);
		$ciudadesRepetidas=["Coruña","Santiago","Ourense","Pontevedra","Verín","Vigo"];
		$cortar=false;
		for($i=0;$i<sizeof($ciudadesRepetidas);$i++){
			$coincidencia =strrpos($estacion,$ciudadesRepetidas[$i]."-");
			if($coincidencia===0){
				$cortar=true;
			}
		}	
		if($cortar){
			$estacion=$estacionCorte[1];
		}
		return $estacion;
	}
	
	//Funcion ordenar concellos alfabeticamente
	function ordenarConcellos($jsonMeteo){
		for($i=0; $i<sizeof($jsonMeteo["listaEstacionsMeteo"]);$i++){
			$idEstacion[]=$jsonMeteo["listaEstacionsMeteo"][$i]["idEstacion"];
			$concello[]=$jsonMeteo["listaEstacionsMeteo"][$i]["concello"];
			$estacion[]=$jsonMeteo["listaEstacionsMeteo"][$i]["estacion"];
		}
		array_multisort($estacion,$concello,$idEstacion);
		array_multisort($concello,$estacion,$idEstacion);
		for($i=0; $i<sizeof($jsonMeteo["listaEstacionsMeteo"]);$i++){
			$resultado[]=[$idEstacion[$i],$concello[$i],$estacion[$i]];		
		}
		return $resultado;
	}
	
	//Función para crear el select ed meteoGalicia
	function crearSelectEstaciones($datosEstaciones,$seleccionado){
		print '								<option value="-1"';
		if($seleccionado=="-1"){
			print 'selected';
		}
	    print '>Seleccione su Estacion Metereológica</option>'."\n";	
	    for($i=0; $i<sizeof($datosEstaciones);$i++){
		     print '								<option value="'.$datosEstaciones[$i][0].'"';
			 if($seleccionado==$datosEstaciones[$i][0]){
				 print 'selected';
			 }
			print '>'.nombrarConcellos($datosEstaciones[$i][1]).' - '.nombrarEstacion($datosEstaciones[$i][2]).'</option>'."\n";	
	    }	
	}
	
	
	if(isset($_GET)){
		if(isset($_GET["idEstacion"]) && comprobarId($_GET["idEstacion"],$jsonMeteo)){
			$idSeleccionado=$_GET["idEstacion"];
			$datosEstacion=recogerEstacion($idSeleccionado);
		}
	}
	
	function creaLogin(){
		if($_SERVER["SERVER_NAME"]=="localhost" || $_SERVER["SERVER_NAME"]=="127.0.0.1" ){
			if(isset($_POST["nick"])){
				if(DB::comprobarNick($_POST["nick"])){
					if(DB::verificaUsuario($_POST["nick"], $_POST["pass"])){
						header("Location: index.php?accion=perfil");
						die();
					}else{
						$errorLogin="La contraseña no es correcta!";
					}
				}else{
					$errorLogin="No existe el usuario!";
				}
			}
		}
		print '	<fieldset id="autenticarse">'."\n";
		print '					<form method="post" action="index.php" autocomplete="off">'."\n";
		print '						<legend>Zona de Autenticación</legend>'."\n";
		print '						<label id="labelnick" for="nick">Nick:</label>'."\n";
		print '						<input type="text" id="nick" name="nick" required maxlength="16">'."\n";
		print '						<label for="pass">Password:</label>'."\n";
		print '						<input type="password" id="pass" name="pass" required maxlength="32">'."\n";
		print '						<span>';
		if(isset($errorLogin)){print $errorLogin;};
		print'</span>'."\n";
		print '						<input type="checkbox" id="recordar" name="recordar">'."\n";
		print '						<label id="recordarL" for="recordar">¿Recordarme?</label>'."\n";
		print '						<input type="submit" id="loguearse" value="Loguearse" />'."\n";	
		print '					</form>'."\n";
		print '				</fieldset>'."\n";
		print '					<nav id="registrate">'."\n";
		print '						<a href="'.linkRegistro().'"> CLICK AQUÍ PARA REGISTRARSE!!</a>'."\n";	
		print '					</nav>'."\n";
		print '				</div>'."\n";
	}
	
	function generarEnlaces(){
		if(isset($_GET["accion"])){
			if($_GET["accion"]=="registrarse"){
				print ' > <a href="index.php?accion=registrarse">Registro</a>'."\n";
			}else if(isset($_GET["idEstacion"])){
				print ' > <a href="index.php?idEstacion='.$_GET["idEstacion"].'">Datos de estación</a>'."\n";
			}
		}
	}
	
	function creaMenuUsuario($datosSesion){
		$datosUsuario=DB::verificaUsuario($_SESSION['nick'],$_SESSION['pass']);
		if($datosUsuario['rol']=="usuario" || $datosUsuario['rol']=="guest"){
			print '<div id="datosPerfilMini">'."\n"; 
			print ' 	<img id="fotoPerfilGMini" src="img/usuario.png" >'."\n";
			print ' 	<p id="NusuarioMini">'.$datosUsuario['nick'].'<p>'."\n";			
			print ' 	<p id="nTemperaturasMini">Temperaturas Guardadas: '.sizeof(DB::obtenerTempUsuario($datosUsuario['id'])).'<p>'."\n";
			print ' 	<a href="index.php?accion=registrarse">Preferencias del usuario</a>'."\n";
			print ' 	<a href="index.php?accion=perfil">Estaciones / Históricos</a>'."\n";
			print ' 	<input type="submit" id="logout" name="logout" value="Desconectar!" />'."\n";
			print '</div>'."\n";
		}else{
			
			
		}
	}
		
	
?>