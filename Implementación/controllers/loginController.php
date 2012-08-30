<?php

class loginController extends Controller{
	
	
	private $_usuario;
	
	public function __construct(){
		parent::__construct();
		$this->_usuario = $this->loadModel('usuario');
	}
	
	
	public function index(){ //RESPONSE
		$this->_view->titulo = "Login de usuario";
		$this->_view->renderizar('formularioLogin','login');
	}
	
	public function validarDatos(){
		$errores = array();
		
		if($_POST){
			if($_POST['guardar'] == 1){
				$this->_usuario->setLogin($_POST['email'], $_POST['contrasena']);
				if(!$this->esCadena($this->_usuario->email))
					$errores['email'] = 'Porfavor introducta un email valido';
				if(!$this->esCadena($this->_usuario->contrasena))
					$errores['contrasena'] = 'Porfavor introducta un contrasena valida';
				if(count($errores))
					$this->error($errores);  //Carga la interfaz formulario error
				
					if($this->_usuario->iniciarSesion())
						$this->mostrarMuro();
					else 
						$this->error();
			}
		}
		
	} //REQUEST
	

	public function cerrarSesion(){
		$this->_usuario->cerrarSesion();
		$this->redireccionar('');	
	}
	
	public function mostrarMuro(){
		$this->redireccionar('muro');
	}

	public function error($errores = array()){
		$this->_view->titulo = "Error en el login";
		$this->_view->errores = $errores;
		$this->_view->usuario = $this->_usuario;
		$this->_view->renderizar('errorLogin','login');
	}
	
}