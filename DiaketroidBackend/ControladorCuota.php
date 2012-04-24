<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'DB/SocioBD.php';
require_once 'DB/CuotaBD.php';
$socioBD = SocioBD::getInstancia();
if(isset($_COOKIE['hash']) && $socioOID=$socioBD->existeHash($_COOKIE['hash'])){
	$tarea=htmlspecialchars(trim($_POST['tarea']));
	if($tarea=="consultar"){
		$cuotaBD = CuotaBD::getInstancia();
		if($datos=$cuotaBD->obtenerDatos($socioOID)){
			$datos->estado="OK";
			exit(json_encode($datos));
		} else {
			exit('{"estado":"error","msg":"Ha ocurrido un error al obtener los datos de la cuota"}');
		}
		
	}
	
} else {
	exit('{"estado":"error","msg":"No estas identificado en el sistema"}');
}