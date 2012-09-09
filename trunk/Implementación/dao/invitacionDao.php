<?php

class invitacionDao{
	
	public $conn;
	
	public function __construct(){
		$this->conn = new Datasource();
	}
	

	function invitarAmigo($id){
		 
		$sql = "INSERT INTO  `facebook`.`invitaciones` (`invita` ,`invitado`)
	    			VALUES ('".Session::get('id')."',  '$id')";
			
		$result = $this->databaseUpdate($sql);
	
		if ($result != 1)
			return false;
		 
		return true;
	}
	
	
	function muestraInvitaciones($id){
		$sql = "SELECT id, nombre,apellido, email FROM usuarios, invitaciones where id=invita and invitado=$id";
			
		$result = $this->databaseUpdate($sql);
		
		$searchResults = array();
		
		while ($row = $this->conn->nextRow($result)) {
			 
			$temp = new usuarioModel();
			 
			$temp->setId($row[0]);
			$temp->setNombre($row[1]);
			$temp->setApellido($row[2]);
			$temp->setEmail($row[3]);
			 
			array_push($searchResults, $temp);
		}
		if(count($searchResults))
			return $searchResults;
		
		return false;

	}
	
	
	function aceptaInvitacion($invitacion){
		
		$sql = "DELETE FROM `invitaciones` WHERE `invita`='".$invitacion->idInvitado."' and  `invitado`='".$invitacion->idMio."'";

		$result = $this->databaseUpdate($sql);
				
		$sql = "INSERT INTO `relaciones` set id='".$invitacion->idMio."' , idbis='".$invitacion->idInvitado."'";
										
		$result = $this->databaseUpdate($sql);

		return true;
		
	}
	
	function databaseUpdate(&$sql) {
	
		$result = $this->conn->execute($sql);
	
		return $result;
	}
}

?>