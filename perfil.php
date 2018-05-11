<?php 
 
	$datosUsuario=DB::verificaUsuario($_SESSION['nick'], $_SESSION['pass']);
	$datosEstacion=recogerEstacion($datosUsuario['idEstacion']);
	
	if(isset($_POST)){
		if(isset($_POST['guardarDatos'])){
			$opcion="guardar";
			$boton=1;
			DB::meterTemperatura($datosUsuario['id'],$datosUsuario['idEstacion'],$datosEstacion["listEstadoActual"][0]["valorTemperatura"]);
		}else if(isset($_POST['verHistorico'])){
			$opcion="historico";
			$boton=2;
			$temperaturas=DB::obtenerTempUsuario($datosUsuario["id"]);		
		}else if(isset($_POST['cambiarEstacion'])){
			$cambiar="estacion";
			$boton=3;
			
		}else if(isset($_POST['borrarHistorico'])){
			DB::borraHistoricoTemp($datosUsuario['id']);
			$opcion="historico";
			$boton=2;
			$temperaturas=DB::obtenerTempUsuario($datosUsuario["id"]);
		}else{
			$opcion="";
			$boton="0";
		}		
	}else{
		$opcion="";
		$boton="0";
	}
	
		//Función para crear el historico de las temperaturas
		function crearHistoricoPerfil($temperaturasDB){
			if(sizeof($temperaturasDB)==0){
				print '<h1 id="tituloHist">Historico de temperaturas</h1>';
				print '<h2 id="subtituloHist">No hay datos que mostrar</h2>';
			}else{
				print '<div id="historicoPerf">'."\n";
				print '<table sumary="Tabla que muestra el historico del perfil" id="historicoTempPerfil">'."\n";
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
					if($i%2==0){$clase="parP";}else{$clase="imparP";};				
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
				print '</div>'."\n";
			}
		}
		
		function muestraBotones($boton){
			echo$boton;
			if($boton==0||$boton==1){
				print'	<div id="botonesCambiosPerfil">'."\n";
				print'		<form action="index.php?accion=perfil" method="post">'."\n";
				print'			<input type="submit" id="guardarDatos"  name="guardarDatos" value="Guardar Datos" />'."\n";
				print'		</form>'."\n";
				print'		<form action="index.php?accion=perfil" method="post">'."\n";
				print'			<input type="submit" id="verHistorico" name="verHistorico" value="Ver Histórico" />'."\n";
				print'		</form>'."\n";
				print'		<form action="index.php?accion=perfil" method="post">'."\n";
				print'			<input type="submit" id="cambiarEst" name="cambiarEstacion" value="Cambiar Estación" />'."\n";
				print'		</form>'."\n";
				print'	</div>'."\n";
			}else if($boton==2){
				print'	<div id="botonesCambiosHistorico">'."\n";
				print'		<form action="index.php?accion=perfil" method="post">'."\n";
				print'			<input type="submit" id="guardarDatos"  name="principal" value="Volver a Estacion" />'."\n";
				print'		</form>'."\n";
				print'		<form action="index.php?accion=perfil" method="post">'."\n";
				print'			<input type="submit" id="borrarHistorico" name="borrarHistorico" value="Borrar Histórico" />'."\n";
				print'		</form>'."\n";
				print'		<form action="index.php?accion=perfil" method="post">'."\n";
				print'			<input type="submit" id="cambiarEst" name="cambiarEstacion" value="Cambiar Estación" />'."\n";
				print'		</form>'."\n";
				print'	</div>'."\n";
			}else if($boton==3){
				
			}
			
			
			
			
		}
		function crearMapaPerfil($datosUsuario){
			print ' <input type="text" id="mapaEstacion" value="'.$datosUsuario['idEstacion'].'" hidden>'."\n"; 
			print ' <div id="mapa">'."\n";
			print ' </div>'."\n";
		}
		function crearPerfil($datosUsuario){
			print '<div id="datosPerfil">'."\n"; 
			print ' 	<p id="Nusuario">'.$datosUsuario['nick'].'<p>'."\n";
			print ' 	<img id="fotoPerfilG" src="img/usuario.png" >'."\n"; 
			print ' 	<p id="nTemperaturas">Temperaturas Guardadas: '.sizeof(DB::obtenerTempUsuario($datosUsuario['id'])).'<p>'."\n";
			print '</div>'."\n";
		}
	
?>
<section id="contenido">
<?php
			if(isset($opcion)){
				crearMapaPerfil($datosUsuario);
			}else if(isset($cambiar)){
				crearPerfil($datosUsuario);
			}else{
				crearMapaPerfil($datosUsuario);
			}
?>
	<div id="resultados">
			<?php 
				if(isset($opcion)){
					if($opcion=="guardar"){
						crearDatosEstacion($datosEstacion);
						print '<span class="datosGuardados"> Los datos se han guardado correctamente</span>';
					}else if($opcion=="historico"){
						crearHistoricoPerfil($temperaturas);
						if(isset($_POST["borrarHistorico"])){
							print '<span class="datosGuardados"> Los datos se han borrado correctamente</span>';
						}
					}else{
						crearDatosEstacion($datosEstacion);
					}				
				}else if(isset($cambiar)){
					echo "hola";
				}else{
					crearDatosEstacion($datosEstacion);
				}
			?>
	</div>
	<?php muestraBotones($boton);?>
</section>