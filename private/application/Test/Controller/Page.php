<?
	namespace Application\Test\Controller;

	class Page extends \Controller{

		function test(){
			echo '<h1>Data of model:</h1>';

			$this->model = new \Application\Test\Model\Users();
			$this->model->findBy_id_user(2);

			print_r($this->model->data());
		}

		function index($args = []){
			$this->view = new \Application\Test\View\HtmlTemplate();
			
			echo $this->view->content([]);
		}
	}