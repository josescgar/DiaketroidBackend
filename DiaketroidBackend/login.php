<?php
header('Content-Type: application/json');
require_once 'DB/SocioBD.php';
$socioDB = SocioDB::getInstancia();

if(isset($_COOKIE['hash']) && $socioDB->existeHash($_COOKIE['hash'])){
	exit('{"error":"Ya estas logeado"}');
} else {
	$usuario=htmlspecialchars(trim($_POST['username']));
	$password=htmlspecialchars(trim($_POST['password']));
	
	$socio=$socioDB->identificarse($usuario,$password);
	
	if($socio){
		$hash = md5(uniqid(microtime().rand()));
		if($socioDB->insertarHash($socio->OID,$hash)){
			setcookie("hash",$hash,time()+(86400*30));
			exit('{"estado":"OK"}');
		} else {
			exit('{"error":"Ha ocurrido un problema durante la identificacion"}');
		}
			
	} else {
		exit('{"error":"Nombre de usuario o contraseña incorrectos"}');
	}
}