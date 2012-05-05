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
	
	public function modificarDatos($socioOID,$socio){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			
			$stmt=$conex->prepare("UPDATE Socio SET Usuario=:usuario, Contraseña=:password WHERE OID=:oid");
			$stmt->bindParam(":usuario",utf8_decode($socio->usuario));
			$stmt->bindParam(":password",$socio->password);
			$stmt->bindParam(":oid",$socioOID);
			$stmt->execute();
			
			$stmt=$conex->prepare("UPDATE C_Persona SET Nombre=:nombre, Apellidos=:apellidos, FechaNacimiento=:fechaNacimiento, Nacionalidad=:nacionalidad, Sexo=:sexo WHERE OID=:oid");
			$stmt->bindParam(":nombre",utf8_decode($socio->nombre));
			$stmt->bindParam(":apellidos",utf8_decode($socio->apellidos));
			$stmt->bindParam(":nacionalidad",utf8_decode($socio->nacionalidad));
			$stmt->bindParam(":fechaNacimiento",$socio->fechaNacimiento);
			$stmt->bindParam(":sexo",$socio->sexo);
			$stmt->bindParam(":oid",$socioOID);
			$stmt->execute();
			
			$stmt=$conex->prepare("UPDATE Colaborador SET Direccion=:direccion, Localidad=:localidad, Provincia=:provincia, CP=:codigoPostal, Email=:email, TelefonoFijo=:fijo, TelefonoMovil=:movil WHERE OID=:oid");
			$stmt->bindParam(":direccion",utf8_decode($socio->direccion));
			$stmt->bindParam(":localidad",utf8_decode($socio->localidad));
			$stmt->bindParam(":provincia",utf8_decode($socio->provincia));
			$stmt->bindParam(":fijo",utf8_decode($socio->telefonoFijo));
			$stmt->bindParam(":movil",utf8_decode($socio->telefonoMovil));
			$stmt->bindParam(":codigoPostal",$socio->codigoPostal);
			$stmt->bindParam(":email",$socio->email);
			$stmt->bindParam(":oid",$socioOID);
			$stmt->execute();
			
			DriverBD::getInstancia()->desconectar();
			return true;
		}catch(PDOException $e){
			echo $e->getMessage().$e->getLine();
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