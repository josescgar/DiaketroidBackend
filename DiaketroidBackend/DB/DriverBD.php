<?php
/**
 * 
 * Driver de la base de datos para la comunicación con esta
 * @author Jose A. Escobar
 *
 */
class DriverBD{
	private static $instancia = null;
	private $conexion = null;
	
	private final $direccion="localhost";
	private final $baseDeDatos="b13_10564860_diaketas";
	private final $usuario="b13_10564860";
	private final $password="diaketas";
	
	private function __construct(){}
	
	public static function getInstancia(){
		if(self::$instancia==null){
			self::$instancia == new DriverBD();
		}
		return self::$instancia;
	}
	
	
	private function conectar(){
		try{
			$this->conexion=new PDO("mysql:host=$this->direccion;dbname=$this->baseDeDatos",$this->usuario,$this->password);
			$this->conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $this->conexion;
		}catch(PDOException $e){
			return false;
		} 
	}
	
	private function desconectar(){
		$this->conexion=null;
	}
}
?>