<?php
require_once 'DriverBD.php';
class SocioDB{
	private static $instancia = null;

	private function __construct(){
	}

	public static function getInstancia(){
		if(self::$instancia==null){
			self::$instancia == new DriverBD();
		}
		return self::$instancia;
	}
	
	public function identificarse($usuario,$password){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$stmt=$conex->prepare("SELECT * FROM Socio WHERE Usuario=:usuario AND Contraseña=:password");
			$stmt->bindParam(":usuario",$usuario);
			$stmt->bindParam(":password",$password);
			$stmt->execute();
			$rows=$stmt->rowCount();
			$user = $stmt->fetch(PDO::FETCH_OBJ);
			DriverBD::getInstancia()->desconectar();
			if($rows==1)
				return $user;
			else
				return false;
		}catch(PDOException $e){
			return false;
		}
	}
	
	public function existeHash($hash){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$stmt=$conex->prepare("SELECT * FROM Hash WHERE hash=:hash");
			$stmt->bindParam(":hash",$hash);
			$stmt->execute();
			$rows=$stmt->rowCount();
			DriverBD::getInstancia()->desconectar();
			if($rows==1)
				return $true;
			else
				return false;
		}catch(PDOException $e){
			return false;
		}
	}
	
	public function insertarHash($socioOID,$hash){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$stmt=$conex->prepare("INSERT INTO Hash (socioOID,hash) VALUES (:socioOID,:hash)");
			$stmt->bindParam(":socioOID",$socioOID);
			$stmt->bindParam(":hash",$hash);
			$stmt->execute();
			return true;
		}catch(PDOException $e){
			return false;
		}
	}
}
?>