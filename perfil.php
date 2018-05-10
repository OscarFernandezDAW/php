<section id="contenido">
	<input type="text" id="mapaEstacion" value="10050" hidden> 
	<div id="mapa">
		
	</div>
	<div id="resultados">
		<div id="arribaDerecha">
			<?php 				
				$datosEstacion=recogerEstacion("10050");
				crearDatosEstacion($datosEstacion);
			?>
		</div>
	</div>
</section>