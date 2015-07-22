<?
	namespace Application\Test\Controller;

	class Page extends \Controller{

		function __construct(){
			\CO::RE()->ARR('js');
			\CO::RE()->ARR('css');
		}

		function test(){
			echo '<h1>Data of model:</h1>';

			$this->model = new \Application\Test\Model\User();
			$this->model->findBy_id_user(2);

			print_r($this->model->data());
		}

		private function htmlOut($content = ''){
			$this->view = new \Application\Test\View\HtmlTemplate();
			
			echo $this->view->get(['content' => $content]);
		}

		function index($args = []){
			$this->htmlOut();
		}

		function admin($args = []){
			$this->model = new \Application\Test\Model\Page\Admin();
			$this->view = new \Application\Test\View\Page\Admin();
			
			$this->htmlOut( $this->view->get( $this->model->data() ) );
		}

		function shop($args = []){
			$this->model = new \Application\Test\Model\Page\Shop();
			$this->view = new \Application\Test\View\Page\Shop();
			
			$this->htmlOut( $this->view->get( $this->model->data() ) );
		}
	}