<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'DB/SocioBD.php';
$socioBD = SocioBD::getInstancia();
if(isset($_COOKIE['hash']) && $socioOID=$socioBD->existeHash($_COOKIE['hash'])){
	$tarea=htmlspecialchars(trim($_POST['tarea']));
	if($tarea=="consultar"){
		if($datos=$socioBD->obtenerDatos($socioOID)){
			$datos->estado="OK";
			exit(json_encode($datos));
		} else {
			exit('{"estado":"error","msg":"Ha ocurrido un error al obtener los datos del socio"}');
		}
		
	}
	
} else {
	exit('{"estado":"error","msg":"No estas identificado en el sistema"}');
}