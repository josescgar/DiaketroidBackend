<?php
require_once 'DriverBD.php';
class SocioBD{
	private static $instancia;

	private function __construct(){
	}

	public static function getInstancia(){
		if (  !self::$instancia instanceof self)
		{
			self::$instancia = new self;
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
	
	public function obtenerDatos($socioOID){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$conex->exec("SET CHARACTER SET utf8");
			$stmt=$conex->prepare("SELECT * FROM Socio s, C_Persona p, Colaborador c
									 WHERE s.OID=:oid AND p.OID=s.OID AND c.OID=p.OID");
			$stmt->bindParam(":oid",$socioOID);
			$stmt->execute();
			$datos = $stmt->fetch(PDO::FETCH_OBJ);
			DriverBD::getInstancia()->desconectar();
			return $datos;
		}catch(PDOException $e){
			return false;
		}
	}
	
	public function desconectarse($hash){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$stmt=$conex->prepare("DELETE FROM Hash WHERE hash=:hash");
			$stmt->bindParam(":hash",$hash);
			$stmt->execute();
			DriverBD::getInstancia()->desconectar();
			return true;
		}catch(PDOException $e){
			return false;
		}
	}
	
	public function existeHash($hash){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$stmt=$conex->prepare("SELECT socioOID FROM Hash WHERE hash=:hash");
			$stmt->bindParam(":hash",$hash);
			$stmt->execute();
			$rows=$stmt->rowCount();
			$socio = $stmt->fetch(PDO::FETCH_OBJ);
			DriverBD::getInstancia()->desconectar();
			if($rows==1)
				return $socio->socioOID;
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