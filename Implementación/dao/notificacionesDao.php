<?php

class notificacionesDao{
	
	public $conn;
	
	public function __construct(){
		$this->conn = new Datasource();
	}
	
	
	function guardar($notificaciones,$default=false){
		
		
		if($default)
			$sql = "INSERT INTO `notificaciones` SET usuario='".Session::get('id')."', `mensaje`='".$notificaciones->mensaje."',  
					`post`='".$notificaciones->post."',  `etiqueta`='".$notificaciones->etiqueta."'";
		
		else
			$sql = "UPDATE `notificaciones` SET `mensaje`='".$notificaciones->mensaje."',  `post`='".$notificaciones->post."',  `etiqueta`='".$notificaciones->etiqueta."'
					WHERE usuario='".Session::get('id')."'";
			
		//echo $sql;exit;
		$result = $this->databaseUpdate($sql);
				
		return true;
		
	}
	
	function traer($notificaciones){
	
		$sql = "SELECT mensaje, post, etiqueta FROM  `notificaciones` WHERE usuario =".Session::get('id');
	
		$result = $this->databaseUpdate($sql);
			
		if($row = $this->conn->nextRow($result)){
			 
			$notificaciones->etiqueta = $row[2];
			$notificaciones->mensaje = $row[0];
			$notificaciones->post = $row[1];

			return $notificaciones;
		}
				
		return false;
	}
	
	
	
	
	function databaseUpdate(&$sql) {
	
		$result = $this->conn->execute($sql);
	
		return $result;
	}
}

?>