<?php 
class muroController extends Controller{
	
	public function __construct(){
		parent::__construct();
		
	}
	
	public function index(){
		$this->_view->titulo = "Muro";
		$this->_view->renderizar('muro','muro');
	}
	
	
}
?>