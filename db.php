<?php
	//Configuración de Bases de datos
	class DB {
		//Ejecuta cualquier consulta que le metamos por parámetro
		protected static function ejecutaConsulta($consulta) {
			$opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
			$ubicacion = "mysql:host=localhost;dbname=meteogalicia";
			$usuario = 'dwes';
			$contrasena = 'abc123.';
			
			$conexion = new PDO($ubicacion, $usuario, $contrasena, $opciones);
			$resultado = null;
			if (isset($conexion)){
				$resultado = $conexion->query($consulta);
			}
			return $resultado;
		}
		
		//Función para obtener los usuarios existentes, metiendole el tipo de ordenación por parámetro
		public static function obtieneUsuarios($ordenacion) {
			$sql = "SELECT id, nick, pass, rol, idEstacion FROM users ".$ordenacion.";";
			$resultado = self::ejecutaConsulta($sql);
			
			$usuarios = array();

			if($resultado) {
					// Añadimos una fila por cada usuario
					$fila = $resultado->fetch(PDO::FETCH_ASSOC);
					while ($fila != null) {
						$usuarios[] = $fila;
						$fila = $resultado->fetch(PDO::FETCH_ASSOC);
					}
			}	
			return $usuarios;
		}
		
		//Función para crear un usuario
		public static function creaUsuario($nick,$pass,$rol,$idEstacion) {
			$sql = "INSERT INTO users (nick,pass,rol,idEstacion) VALUES ('$nick','$pass','$rol','$idEstacion')";
			$resultado = self::ejecutaConsulta($sql);
			return $resultado;
		}
		
		//Función para borrar un usuario
		public static function borraUsuario($id) {
			$sql = "DELETE FROM users WHERE id='$id';";
			$resultado = self::ejecutaConsulta($sql);

			print_r("se ha borrado".$id);
		}
		
		//Función que sirve para devolver la fila de determinado usuario si existe y sino valdrá para decir que no es correcto.
		public static function verificaUsuario($nick, $pass) {
			$sql = "SELECT id, nick, rol, idEstacion FROM users ";
			$sql .= "WHERE nick='$nick' ";
			$sql .= "AND pass='$pass';";
			$resultado = self::ejecutaConsulta($sql);
			
			if(isset($resultado)) {
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
			}
			return $fila; // Devuelve todos los campos de usuario menos el password
		}
		
		//Función que sirve para devolver la fila de determinado usuario si existe y sino valdrá para decir que no es correcto.
		public static function cambiaValor($nick, $columna, $valorCambio) {
			$sql = "UPDATE users ";
			$sql .= "SET $columna='$valorCambio'";
			$sql .= "WHERE nick='$nick';";
			$resultado = self::ejecutaConsulta($sql);
		}
		
		//Función para cambiar contraseña siendo usuario
		public static function recuperarPass($nick, $idEstacion, $passCambio) {
			$sql = "UPDATE users ";
			$sql .= "SET pass='$passCambio'";
			$sql .= "WHERE nick='$nick' AND idEstacion='$idEstacion' ;";
			$resultado = self::ejecutaConsulta($sql);
		}
		
		//Función para saber si existe un usuario
		public static function comprobarNick($nick) {
			$sql = "SELECT * "; 
			$sql .= "FROM users ";
			$sql .= "WHERE nick='$nick' ;";
			$resultado = self::ejecutaConsulta($sql);
			$coincidencia=false;
			if(isset($resultado)) {
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				if($fila!==false){
					$coincidencia=true;
				}
			}
			return $coincidencia;
		}
		
		//Función para saber ultimas 10 temperaturas
		public static function obtener10Temp() {
			$sql = "SELECT id_estacion, valTemperatura "; 
			$sql .="FROM temperaturas ";
			$sql .="ORDER BY id DESC limit 10";
			$resultado = self::ejecutaConsulta($sql);
			$temperatuas;
			if(isset($resultado)) {
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				while ($fila != null) {
					$temperaturas[]=[$fila["id_estacion"],$fila["valTemperatura"]];
					$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				}	
			}
			return $temperaturas;
		}
		
		
	}
	//DB::creausuario("kaka","mierda","culo","1212");
	//DB::obtener10Temp();
?>