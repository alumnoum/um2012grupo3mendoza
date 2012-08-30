<?php 

class View{
	
	private $_controlador;
	
	public function __construct(Request $peticion){
		$this->_controlador = $peticion->getControlador();
	}
	
	public function renderizar($vista,$item = false){
		
		if(!Session::get('autenticado'))	 //Menú para usuarios no logueados	
			$menu = array(
				array(
						'id' => 'inicio', 
						'titulo' => 'Inicio',
						'enlace' => BASE_URL 
					),
				array(
						'id' => 'registro',
						'titulo' => 'Registro de usuario',
						'enlace' => BASE_URL . 'registro/'
						),
				array(
						'id' => 'login',
						'titulo' => 'Login',
						'enlace' => BASE_URL . 'login/'
				));
		else                           //Menú para usuarios logueados
			$menu = array(
					array(
							'id' => 'muro',
							'titulo' => 'Muro',
							'enlace' => BASE_URL . 'muro/'
					),
					array(
							'id' => 'config',
							'titulo' => 'Configuracion',
							'enlace' => BASE_URL . 'config/'
					),
					array(
							'id' => 'amistades',
							'titulo' => 'Amistades',
							'enlace' => BASE_URL . 'amistades/'
					),
					array(
							'id' => 'posts',
							'titulo' => 'Posts',
							'enlace' => BASE_URL . 'posts/'
					),
					array(
							'id' => 'cerrarSesion',
							'titulo' => 'Cerrar sesion',
							'enlace' => BASE_URL . 'login/cerrarsesion'
					));
			
		$_layoutParams = array( 
				'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/',
				'ruta_imgs' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/',
				'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/',
				'menu' => $menu,
				);
		
		$rutaView = ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.phtml';
		
		if(is_readable($rutaView)){
			include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php';
			include_once $rutaView;
			include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.php';
				
		}
		else 
			throw new Exception('Error al cargar la vista');
	}	
	
}


?>