<?php

class notificacionesController extends Controller{
	
	private $_notificaciones;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->_notificaciones = $this->loadModel('notificaciones');
		$this->_notificaciones = $this->_notificaciones->traer();
		
		$this->_view->_notificaciones = $this->_notificaciones;
		$this->_view->titulo = "Administrador de notificaciones";
		$this->_view->renderizar('index','config');
		//}
	}
	
	public function guardar(){
		
		$this->_notificaciones = $this->loadModel('notificaciones');
	
		if($_POST){
				
				$this->_notificaciones->post = 0;
				$this->_notificaciones->etiqueta = 0;
				$this->_notificaciones->mensaje = 0;
				
				if(isset($_POST['post']))
					$this->_notificaciones->post = 1;
				if(isset($_POST['mensaje']))
					$this->_notificaciones->mensaje = 1;
				if(isset($_POST['etiqueta']))
					$this->_notificaciones->etiqueta = 1;
				
				if($this->_notificaciones->guardar($this->_notificaciones)){
					$this->_view->titulo = "Los cambios se guardaron correctamente";
					$this->_view->renderizar('estado','config');
					exit;
				}
		}
	}
}

?>