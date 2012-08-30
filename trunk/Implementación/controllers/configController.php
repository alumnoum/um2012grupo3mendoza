<?php

class configController extends Controller{
	
	public function __construct(){
		parent::__construct();
		
	}
	
	public function index(){
		$this->_view->titulo = "Configuracion general de su cuenta";
		$this->_view->renderizar('index','config');
	}
	
}

?>