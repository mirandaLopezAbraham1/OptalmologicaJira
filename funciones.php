<?php
	include_once("conexion.php");
	function validar_usuario($usuario,$clave){
		$con=DB_connect();
		$sql = "Select * from usuarios where usuario= ? and clave= ?";
		$params=array($usuario,$clave);
		$opcion=array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$result=sqlsrv_query($con,$sql,$params,$opcion);
		$filas = sqlsrv_num_rows($result);
		return $filas;
	}
	function user_name($usuario){
		$con=DB_connect();
		$sql="select * from usuarios where usuario = ?";
		$param=array($usuario);
		$result=sqlsrv_query($con,$sql,$param);
		$fila=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		return $fila['nombre'];
	}
	function verificar_paciente($cedula){
		$con=DB_connect();
		$sql = "Select * from pacientes where cedula = ?";
		$params=array($cedula);
		$opcion=array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$result=sqlsrv_query($con,$sql,$params,$opcion);
		$filas = sqlsrv_num_rows($result);
		return $filas;
	}
	function ingreso_paciente($cedula,$nombre,$direccion,$telefono){
		$con=DB_connect();
		$sql="insert into pacientes (cedula,nombre,direccion,telefono)
				values(?,?,?,?)";
		$params=array($cedula,$nombre,$direccion,$telefono);
		$resultado=sqlsrv_query($con,$sql,$params);
		$op=0;
		if($resultado){
			$op=1;
		}
		return $op;
	}
	function historia($cedula){
		$con=DB_connect();
		$sql="insert into historias (cedula)
				values(?)";
		$param=array($cedula);
		$resultado=sqlsrv_query($con,$sql,$param);
	}
	function patient($paciente){
		$con=DB_connect();
		$sql="select *, (select numero from historias where cedula=p.cedula )historia
			 from pacientes p 
			 where cedula = ?";
		$param=array($paciente);
		$result=sqlsrv_query($con,$sql,$param);
		$fila=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		if($fila!=null){
			$cadena="<tr><td>".$fila['historia']."</td>
							<td>".$fila['cedula']."</td>
							<td>".$fila['nombre']."</td>
							<td>".$fila['telefono']."</td>
							<td><button id='".$fila['cedula']."' onClick='actualizar_id(this.id)'
					  		  class='btn btn-warning'>Actualizar Datos</button></td>
					  		  <td><button id='".$fila["cedula"].
					"' onClick='historia_id(this.id)' class='btn btn-secondary'>Ver Historial</button></td>
					<td><button id='".$fila["cedula"].
					"' onClick='consulta_id(this.id)' class='btn btn-success'>Ir a Consulta</button></td></tr>";
			return $cadena;
		}else{
			return "<tr><td colspan='4' align='center'>Paciente no registrado</td></tr>";
		}
	}
?>