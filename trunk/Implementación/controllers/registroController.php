<?php

class registroController extends Controller{
	
	private $_usuario;
	
	public function __construct(){
		parent::__construct();
		$this->_usuario = $this->loadModel('usuario');		
	}
	

	public function index(){ //RESPONSE
		$this->_view->titulo = "Ingrese sus datos para registrarse";
		$this->_view->renderizar('formularioRegistro','registro');
	}
	
	
	public function validar(){  //REQUEST
		
		$errores = array();
		
		if($_POST){				
			if($_POST['guardar'] == 1){							
				$this->_usuario->setUser($_POST['nombre'],$_POST['apellidos'],$_POST['direccion'], $_POST['telefono'], $_POST['email'], $_POST['ciudad'], $_POST['pais'], $_POST['contrasena']);				
				if(!$this->esCadena($this->_usuario->nombre))
					$errores['nombre'] = 'Porfavor introducta un nombre valido';
				if(!$this->esCadena($this->_usuario->email))
					$errores['email'] = 'Porfavor introducta un email valido';
				if(!$this->esCadena($this->_usuario->contrasena))
					$errores['contrasena'] = 'Porfavor introducta un contrasena valida';				
				if(count($errores))
					$this->error($errores);  //Carga la interfaz formulario error
				else
					if($this->_usuario->registrar())
						if($this->_usuario->iniciarSesion())
							$this->redireccionar('muro');
			}
		}
				
	}
	
	public function error($errores = array()){ //RESPONSE
		$this->_view->titulo = "Error en el registro";
		$this->_view->errores = $errores;
		$this->_view->usuario = $this->_usuario;
		$this->_view->renderizar('formularioError','registro');
	}

		
	
	
	//echo $this->_data->get(dameUsuarios);
}

?>