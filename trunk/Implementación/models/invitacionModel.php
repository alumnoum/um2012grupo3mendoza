<?php

class invitacionModel extends Model{
	
	public $idInvitado;
	public $idMio;
	private $_invitacionDao;
		
	public function __construct(){
		parent::__construct();
		require_once ROOT . 'dao' . DS . 'invitacionDao.php';
	}
	
	
	public function invitarAmigo($invitacion)
	{
		$this->_invitacionDao = new invitacionDao();
				
		if($this->_invitacionDao->invitarAmigo($invitacion->idInvitado))
			return true;		

		return false;
	}

	
	public function muestraInvitaciones($invitacion){
		
		$this->_invitacionDao = new invitacionDao();

		$invitaciones = $this->_invitacionDao->muestraInvitaciones($invitacion->idMio);
			return $invitaciones;
		
		return false;
	}
	
	public function aceptaInvitacion($invitacion){

		$this->_invitacionDao = new invitacionDao();
			
			if($this->_invitacionDao->aceptaInvitacion($invitacion))
				return true;

	return false;
	}
	
}

?>