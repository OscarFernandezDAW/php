<?php
require_once'db.php';

$usuarios=DB::obtieneUsuarios("");

function tablaUsuarios($usuarios){
	
	echo'<table sumary="Tabla que muestra toda la información de los usuarios" id="tablaUsuarios">';
		echo"<caption>Tabla de usuarios de la base de datos</caption>";
		echo"<thead>";
		echo"<tr>";
		echo"<th>CHECK</th><th>ID</th><th>NICK</th><th>PASSWORD</th><th>ROL</th><th>IDESTACION</th>";
		echo"</tr>";
		echo"</thead>";
		echo"<tbody>";
		for($i=0; $i<=sizeof($usuarios)-1;$i++){
			echo"<tr>";
			echo'<td><input type="checkbox"></td>';
			echo"<td>".$usuarios[$i]['id']."</td>";	
			echo"<td>".$usuarios[$i]['nick']."</td>";
			echo"<td>".$usuarios[$i]['pass']."</td>";
			echo"<td>".$usuarios[$i]['rol']."</td>";
			echo"<td>".$usuarios[$i]['idEstacion']."</td>";
			echo"</tr>";
		}
		echo"</tbody>";
	echo"</table>";
}

tablaUsuarios($usuarios);
?>