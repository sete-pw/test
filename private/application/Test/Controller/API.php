<?
	namespace Application\Test\Controller;

	class API extends \Controller{
		private $router;

		function __construct(){
			$this->model = new \Application\Test\Model\API();
			$this->view = new \Application\Test\View\Json();
		}

		function index($args = []){

			$this->model->$args['method']($args);
			return $this->view->get( $this->model->data() );
		}
	}