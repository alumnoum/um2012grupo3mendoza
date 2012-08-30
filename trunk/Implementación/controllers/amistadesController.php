<?php

class amistadesController extends Controller{
	
	private $_usuario;
	private $_usuarioDao;
	private $_invitacion;
		
	public function __construct(){
		parent::__construct();
		$this->_usuario = $this->loadModel('usuario');
		
		require_once ROOT . 'dao' . DS . 'usuarioDao.php';
	}
	
	public function index(){
		$this->_view->titulo = "Gestionar amistades";
		$this->_view->renderizar('index','amistades');
	}
	
	public function invitaciones($id=false){

		$this->_invitacion = $this->loadModel('invitacion');
		$this->_invitacion->idMio = Session::get('id');
		
		if($id){  //ACEPTA INVITACION
			$this->_invitacion->idInvitado = $id;
			if($this->_invitacion->aceptaInvitacion($this->_invitacion)){
				$this->_view->titulo = "Ahora sois amigos!!";
				$this->_view->renderizar('estado','amistades');
				exit;
			}
		}

		//MUESTRA INVITACIONES		
		$invitaciones = array();
		$invitaciones = $this->_invitacion->muestraInvitaciones($this->_invitacion);
	
		
		if($invitaciones){
			$this->_view->titulo = "Invitaciones pendientes de confirmacion";
			$this->_view->_invitaciones = $invitaciones;
			$this->_view->renderizar('listaInvitaciones','amistades');
			exit();
		}
		
		$this->_view->titulo = "Gestionar amistades";
		$this->_view->renderizar('index','amistades');		
	}
	
	public function invitar($id = false){
		if(!$id){
			$this->_view->_usuarios = array();
			
			if($_POST){ //Buscar a sus posibles amigos por nombre apellido o email
				$this->_usuarioDao = new usuarioDao();
				$usuarios = $this->_usuarioDao->busqueda(new Datasource(),$_POST['query']);
				$this->_view->_usuarios = $usuarios;
				$this->_view->titulo = "Resultado de la busqueda";
				$this->_view->renderizar('listaInvitar','amistades');
				exit;
			}
			else{ //Entra al menú
				$this->_view->titulo = "Gestionar amistades";
				$this->_view->renderizar('listaInvitar','amistades');
			}
		}
		else 
		{   //Procesa invitacion 
			$this->_invitacion = $this->loadModel('invitacion');
			$this->_invitacion->idInvitado = $id;
				
			if($this->_invitacion->invitarAmigo($this->_invitacion)){
				$this->_view->titulo = "Se realizo la invitacion con exito.";
				$this->_view->renderizar('estado','amistades');
			}
			$this->_view->titulo = "Hubo un problema, consulte al administrador.";
			$this->_view->renderizar('estado','amistades');
		}
	}
	
	public function buscar(){
		if($_POST){
			$this->_usuarioDao = new usuarioDao();
			$usuarios = $this->_usuarioDao->busqueda(new Datasource(),$_POST['query']);
			$this->_view->_usuarios = $usuarios;
			$this->_view->titulo = "Resultado de la busqueda";
			$this->_view->renderizar('lista','amistades');
			exit;
		}
	}
	
	public function eliminar($id=false){
		$this->_usuarioDao = new usuarioDao();
			if(!$id){
				if(!$amigos = $this->_usuarioDao->buscaAmigos(new Datasource(), Session::get('id'))){
					$this->_view->titulo = "No hay amigos que eliminar. Pruebe a buscar nuevos amigos.";
					$this->_view->renderizar('estado','amistades');
					exit;
				}
				$this->_view->titulo = "Amigos";
				$this->_view->amigos = $amigos;
				$this->_view->renderizar('listaEliminar','amistades');
				exit;
			}
			$this->_usuarioDao->eliminarAmigo(new Datasource(),$id);
			$this->redireccionar('amistades/eliminar/');
		}
	}
	


?>