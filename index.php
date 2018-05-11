<?php 
	session_start();
	require_once('meteogalicia.php');
	require_once('db.php');
	require_once('utilidades.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Proyecto Final DWES- Oscar Fernández Rodríguez</title>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css">
	<script  type="text/javascript" src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
	<script  type="text/javascript" src="js/mapa.js"></script>
	<script  type="text/javascript" src="js/jQuery.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
	<header>
		<div id="titulo">
			<img src="img/meteo.png" alt="logo meteogalicia">
			<h1>PROYECTO FINAL DESARROLLO WEB EN CONTORNO SERVIDOR - DAW</h1>
			<h2>Aplicación Web Metereológica</h2>
		</div>
	</header>
	<main id="cuerpo">
		<section id="menu">
			<div id="login">
			<?php 
			if(isset($_SESSION['nick'])){
				creaMenuUsuario($_SESSION);
			}else{
				creaLogin();
			}
			?>
			<div id="historico">
				<?php
					//Recogemos las ultimas 10 temperaturas de la base de datos
					$temperaturasDB=DB::obtener10Temp();
					//Las mostramos
					crearHistorico($temperaturasDB);
				?>
			</div>
		</section>
		<nav id="localizacion">
			<p><b>Usted está en : </b><a href="index.php">Página principal</a><?php generarEnlaces(); ?></p>
		</nav>
	<?php if(isset($_GET["accion"])){
			if($_GET["accion"]=="registrarse"){
				include('registro.php');
			}else if($_GET["accion"]=="perfil"){
				include('perfil.php');
			}
		}else{
			include('general.php');
		}
	?>
	</main>
	<footer>
		<h3 id="creador">Página creada por: <span>Oscar Fernández Rodríguez </span><span id="copy">&copy;</span></h3>	
		<a rel="license" href="http://creativecommons.org/licenses/by/2.0/es/"><img alt="Licencia de Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by/2.0/es/88x31.png" />Esta obra está bajo una licencia de Creative Commons Reconocimiento 2.0 España.</a>
	</footer>
</body>
</html>