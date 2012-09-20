<?php

class usuarioModel extends Model{
	
	public $id;
	public $nombre;
	public $apellido;
	public $direccion;
	public $telefono;
	public $email;
	public $ciudad;
	public $pais;
	public $contrasena;
	public $imagen;
	public $_notificaciones;
		
	public function __construct(){
		parent::__construct();
		require_once ROOT . 'dao' . DS . 'usuarioDao.php';
		require_once ROOT . 'models' . DS . 'notificacionesModel.php';
	}
	
	
	public function iniciarSesion(){
		
		$usuarioDao = new usuarioDao();
		$usuario = new usuarioModel();
		if(! $usuario = $usuarioDao->checkLogin($this))
			return false;

		
		Session::set('autenticado', true);
		Session::set('usuario', $usuario->nombre);
		Session::set('id', $usuario->id);
		Session::set('email', $usuario->email);		
		return true;

		
		return false;
	}
	
	public function cerrarSesion(){
		Session::destroy();
	}
	
	public function registrar(){
		
		$usuarioDao = new usuarioDao();
		
		if(!$usuarioDao->create($this))
				return false;
		
		
		//$this->_notificaciones->setDefaults();
		
		return true;
	}
	
	
	
	public function clonar() {
		$cloned = new Usuario();
	
		$cloned->setId($this->id);
		$cloned->setNombre($this->nombre);
		$cloned->setApellido($this->apellido);
		$cloned->setDireccion($this->direccion);
		$cloned->setTelefono($this->telefono);
		$cloned->setEmail($this->email);
		$cloned->setCiudad($this->ciudad);
		$cloned->setPais($this->pais);
		$cloned->setContrasena($this->contrasena);
	
		return $cloned;
	}
	
	public function setUser($nombre,$apellido,$direccion,$telefono,$email,$ciudad,$pais,$contrasena){
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->direccion = $direccion;
		$this->telefono = $telefono;
		$this->email = $email;
		$this->ciudad = $ciudad;
		$this->pais = $pais;
		$this->contrasena = $contrasena;
	}
	
	public function setLogin($email,$contrasena){
		$this->email = $email;
		$this->contrasena = $contrasena;
	}
	
	
  function getId() {
          return $this->id;
    }
    function setId($idIn) {
          $this->id = $idIn;
    }

    function getNombre() {
          return $this->nombre;
    }
    function setNombre($nombreIn) {
          $this->nombre = $nombreIn;
    }

    function getApellido() {
          return $this->apellido;
    }
    function setApellido($apellidoIn) {
          $this->apellido = $apellidoIn;
    }

    function getDireccion() {
          return $this->direccion;
    }
    function setDireccion($direccionIn) {
          $this->direccion = $direccionIn;
    }

    function getTelefono() {
          return $this->telefono;
    }
    function setTelefono($telefonoIn) {
          $this->telefono = $telefonoIn;
    }

    function getEmail() {
          return $this->email;
    }
    function setEmail($emailIn) {
          $this->email = $emailIn;
    }

    function getCiudad() {
          return $this->ciudad;
    }
    function setCiudad($ciudadIn) {
          $this->ciudad = $ciudadIn;
    }

    function getPais() {
          return $this->pais;
    }
    function setPais($paisIn) {
          $this->pais = $paisIn;
    }

    function setImagen($imagen) {
    	$this->imagen = $imagen;
    }
    
    
    function getContrasena() {
          return $this->contrasena;
    }
    function setContrasena($contrasenaIn) {
          $this->contrasena = $contrasenaIn;
    }
	

	
}

?>