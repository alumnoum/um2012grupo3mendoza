<?php

class postsModel extends Model{
	
	public $id;
	public $contenido;
	public $_permisos;
		
	public function __construct(){
		parent::__construct();
		//require_once ROOT . 'dao' . DS . 'postDao.php';
	}
	
	
	public function nuevo(){

		
	}
	
}

?>