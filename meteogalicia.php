<?php

function obtenerDatosJson($url){
	//  Iniciamos curl
	$curl = curl_init();
	// Desactivamos verificación SSL
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 1 );
	// Devuelve respuesta aunque sea falsa
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	// Especificamo los MIME-Type que son aceptables para la respuesta.
	curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
	// Establecemos la URL
	curl_setopt( $curl, CURLOPT_URL, $url );
	// Ejecutamos curl
	$json = curl_exec( $curl );
	// Cerramos curl
	curl_close( $curl );
	$respuesta = json_decode( $json, true );
	return $respuesta;
}
function obtenerDatos($url){
	//  Iniciamos curl
	$curl = curl_init();
	// Desactivamos verificación SSL
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 1 );
	// Devuelve respuesta aunque sea falsa
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	// Especificamo los MIME-Type que son aceptables para la respuesta.
	curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
	// Establecemos la URL
	curl_setopt( $curl, CURLOPT_URL, $url );
	// Ejecutamos curl
	$respuesta = curl_exec( $curl );
	// Cerramos curl
	curl_close( $curl );
	return $respuesta;
}
//Función para procesar el json de meteogalicia
function recogerJsonMeteo(){
		$jsonMeteo=obtenerDatosJson("http://servizos.meteogalicia.gal/rss/observacion/listaEstacionsMeteo.action");
		return $jsonMeteo;
}
function recogerEstacion($idEstacion){
	$datosEstacion=obtenerDatosJson("http://servizos.meteogalicia.gal/rss/observacion/estadoEstacionsMeteo.action?idEst=".$idEstacion);
	return $datosEstacion;
}

//Funcion para sacar el nombre de la estación a partir de un id
function sacarEstacion($datosEstacion){
	return $nombreEstacion=$datosEstacion["listEstadoActual"][0]["estacion"];	
}
//Funcion para sacar el concello de la estación a partir de un id
function sacarConcello($datosEstacion){
	return $nombreConcello=$datosEstacion["listEstadoActual"][0]["concello"];	
}
//Funcion para sacar las coordenadas de la estación a partir de un id
function sacarCoordenadas($datosEstacion){
	return $coordenadas=[$datosEstacion["listEstadoActual"][0]["lat"],$datosEstacion["listEstadoActual"][0]["lon"]];	
}
//Funcion para sacar la temperatura
function sacarTemp($datosEstacion){
	return $temperatura=[$datosEstacion["listEstadoActual"][0]["lat"],$datosEstacion["listEstadoActual"][0]["valorTemperatura"]];	
}

//Funcion para saber si existe un id
function comprobarId($idEstacion,$jsonMeteo){
	$existe=false;
	for($i=0; $i<sizeof($jsonMeteo["listaEstacionsMeteo"]);$i++){	
		if($idEstacion==$jsonMeteo["listaEstacionsMeteo"][$i]["idEstacion"]){
				$existe=true;
		}
	}

	return $existe;
}
//Funcion para saber todos los estados actuales de las estaciones
function sacarTodo(){
	$respuesta=obtenerDatosJSON("http://servizos.meteogalicia.gal/rss/observacion/estadoEstacionsMeteo.action?idEst=");
	return $respuesta;
}
?>
