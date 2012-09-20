<?php

class postsController extends Controller{
	
	private $_post; 
	private $_permisos;

	public function __construct(){
		parent::__construct();
		$this->_post = $this->loadModel('posts');
		$this->_post->_permisos = $this->loadModel('permisos'); //COMPOSICION
		
	}
	
	public function index(){
		$this->_view->titulo = "Mis POST";
		$this->_view->renderizar('index','post');
	}
	
	public function nuevo(){

		
		if($_POST){ 
			if(!strlen($_POST['contenido'])>0){ //Si envia vacio el POST
				$this->_view->titulo = "Nuevo POST  ::  Debe escrbir algo en el POST";
				$this->_view->renderizar('nuevo','post');
				exit;
			}
			if(!isset($_POST['permisos'])){ //Si no selecciona los permisos
				$this->_view->titulo = "Nuevo POST  ::  Debe selecionar un permiso.";
				$this->_view->contenido = $_POST['contenido'];
				$this->_view->renderizar('nuevo','post');
				exit;
			}
			$this->_post->_permisos->contenido = $_POST['contenido'];
			$this->_post->_permisos->permiso =  $_POST['permisos'];
			//$this->_post->guardar($this->_post);
			//$this->_view->mensaje = 'Se guardó correctamente el post con los permisos';
			//$this->_view->renderizar('exito','post');
		}
		$this->_view->titulo = "Nuevo POST";
		$this->_view->renderizar('nuevo','post');
	}
	
}

?>