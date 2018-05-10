//Funcion que crea la lista de pois  mediante el php
function posicionEstacion(idEstacion){

	var posicion=JSON.parse($.ajax({
		type: "POST", //Los pasamos por post
		url: "utilidades.php", // el php al que mandamos los datos
		data: {recogerPosicion: idEstacion}, //Enviamos los datos del id
		async:false,
    }).responseText);
	return posicion;
}
function recogerDatos(){

	var datos=JSON.parse($.ajax({
		type: "POST", //Los pasamos por post
		url: "utilidades.php", // el php al que mandamos los datos
		data: {recogerDatos: "Damelos!"}, //Enviamos los datos del id
		async:false,
    }).responseText);
	return datos;
}
function crearMapa(lat,longitud,zoom){
	var mapa = L.map('mapa').setView([lat,longitud], zoom);
			L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiZXJlc21hbG8iLCJhIjoiY2pncDVvc3BzMDB0bTMzb2ZhYWZoZWswcyJ9.BG1bIJ-J9TlF6HQVHORvVg', {
				maxZoom: 15,
			id: 'mapbox.streets',
			accessToken: 'pk.eyJ1IjoiZXJlc21hbG8iLCJhIjoiY2pncDVvc3BzMDB0bTMzb2ZhYWZoZWswcyJ9.BG1bIJ-J9TlF6HQVHORvVg'
			}).addTo(mapa);
	return mapa;
}
function mostrarMapa(){
	if($("#selectEstacion").val()=="-1"){
			var mapa=crearMapa(42.8101996,-7.9797891,7.6);
			var datos=recogerDatos();
			for(var i=0; i<datos["listEstadoActual"].length; i++){
				window["ico"+i] = L.icon({
					iconUrl: 'http://www.meteogalicia.gal/datosred/infoweb/meteo/imagenes/meteorosmapa/ceo/'+datos["listEstadoActual"][i]["lnIconoCeo"]+'.png',
					iconSize: [35, 35],
					});
					window["ico"+i] = L.marker([datos["listEstadoActual"][i]["lat"],datos["listEstadoActual"][i]["lon"]],{icon: window["ico"+i]});
					window["ico"+i].addTo(mapa)
					window["ico"+i].bindPopup('<b>Estación Metereolóxica:</b><br><a href="index.php?idEstacion='+datos["listEstadoActual"][i]["idEstacion"]+'">'+datos["listEstadoActual"][i]["estacion"]+'</a><br>Temperatura:'+datos["listEstadoActual"][i]["valorTemperatura"]+'ºC<br>Sensación Térmica: '+datos["listEstadoActual"][i]["valorSensTermica"]+'ºC');			
			}
	}else{
		if($("#selectEstacion").length>0){
			var idEstacion=$("#selectEstacion").val();
		}else{
			var idEstacion=$("#mapaEstacion").val();
		}
		var posicion=posicionEstacion(idEstacion);

		var mapa=crearMapa(posicion[0],posicion[1],11);
		var icono = L.icon({
				iconUrl: 'img/antena.png',
				iconSize: [40, 40], 
			});
			icono=L.marker([posicion[0],posicion[1]], {icon: icono});
			icono.addTo(mapa);		
	}
}
window.addEventListener("load",mostrarMapa,false); 