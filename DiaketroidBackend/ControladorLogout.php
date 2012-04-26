<?php
header('Content-Type: application/json');
require_once 'DB/SocioBD.php';
$socioBD = SocioBD::getInstancia();
if(isset($_COOKIE['hash'])){
	$socioBD->desconectarse($_COOKIE['hash']);
	setcookie("token","",time()-3600); //Expire cookie on the device
	exit('{"estado":"OK"}');
} else {
	exit('{"estado":"error","msg":"No está identificado"}');
}
?>