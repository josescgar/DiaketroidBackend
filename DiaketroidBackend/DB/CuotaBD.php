<?php
require_once 'DriverBD.php';
class CuotaBD{
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
	
	public function obtenerDatos($socioOID){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$conex->exec("SET CHARACTER SET utf8");
			$stmt=$conex->prepare("SELECT * FROM Cuota WHERE OIDSocio=:oid AND FechaFin IS NULL");
			$stmt->bindParam(":oid",$socioOID);
			$stmt->execute();
			$datos = $stmt->fetch(PDO::FETCH_OBJ);
			DriverBD::getInstancia()->desconectar();
			return $datos;
		}catch(PDOException $e){
			return false;
		}
	}
	
	public function cancelarCuota($socioOID){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$stmt=$conex->prepare("UPDATE Cuota SET FechaFin=:fecha WHERE OIDSocio=:oid AND FechaFin IS NULL");
			$fecha = date("Y-m-d",time());
			$stmt->bindParam(":fecha",$fecha);
			$stmt->bindParam(":oid",$socioOID);
			$stmt->execute();
			DriverBD::getInstancia()->desconectar();
			return true;
		}catch(PDOException $e){
			return false;
		}
	}
	
	public function modificarCuota($socioOID,$OID,$cantidad,$intervaloPagos,$fechaFin){
		try{
			$conex=DriverBD::getInstancia()->conectar();
			$stmt=$conex->prepare("UPDATE Cuota SET FechaFin=:fecha WHERE OID=:oid");
			$stmt->bindParam(":oid",$OID);
			$stmt->bindParam(":fecha",$fechaFin);
			$stmt->execute();
			$stmt=$conex->prepare("INSERT INTO Cuota (OIDSocio,Cantidad,IntervalosPagos,FechaInicio) VALUES (:oid,:cantidad,:intervalo,:fecha)");
			$stmt->bindParam(":oid",$socioOID);
			$stmt->bindParam(":cantidad",$cantidad);
			$stmt->bindParam(":intervalo",$intervaloPagos);
			$stmt->bindParam(":fecha",$fechaFin);
			$stmt->execute();
			DriverBD::getInstancia()->desconectar();
			return true;
		}catch(PDOException $e){
			return false;
		}
		
	}
}
?>