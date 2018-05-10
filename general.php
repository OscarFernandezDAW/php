<section id="contenido">
	<div id="estaciones">
		<form action="index.php" method="get">
			<fieldset id="estacionesMeteo">
				<legend>Estaciones Meteorológicas</legend>
					<select id="selectEstacion" name="idEstacion">
				<?php 
					crearSelectEstaciones($datosEstaciones,$idSeleccionado); 
				?>
				</select>
				<input type="submit" value="Pedir datos"/>
			</fieldset
		</form>
	</div>
	<div id="mapa">
		
	</div>
	<div id="resultados">
		<div id="arribaDerecha">
			<?php 
			if($idSeleccionado==-1){
				crearTempDiaria($paginaMeteo);
			}else{
				crearDatosEstacion($datosEstacion);
			}
			?>
		</div>
		<div id="abajoIzquierda">
			<?php
				if($idSeleccionado==-1){
					crearInformacion();
				}
			?>	
		</div>
	</div>
</section>