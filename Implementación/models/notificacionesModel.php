<?php

class notificacionesModel extends Model{
	
	
	public $post;
	public $mensaje;
	public $etiqueta;
	
public function __construct(){
		parent::__construct();
		require_once ROOT . 'dao' . DS . 'notificacionesDao.php';
	}
	
	
	public function guardar(notificacionesModel $notificaciones){
		
		$notificacionesDao = new notificacionesDao();
		
		if($notificacionesDao->guardar(new Datasource(), $notificaciones))
			return true;
		
		return false;
	}
		
	function traer(){
	
		$notificacionesDao = new notificacionesDao();
		$notificaciones = $notificacionesDao->traer(new Datasource(), $this);
		
		if(!$notificaciones){ //Si nunca guardó la config devuelvo la que hay por defecto.
			$this->post = 1; $this->mensaje = 1; $this->etiqueta = 1; 
			$notificaciones = $this;
		}
		return $notificaciones;
	}
	
	function setAll($post, $mensaje, $etiqueta){
		
		$this->post = $post;
		$this->mensaje = $mensaje;
		$this->etiqueta = $etiqueta;
		
	}
	

}
	
	
