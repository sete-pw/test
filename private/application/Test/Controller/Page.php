<?
	namespace Application\Test\Controller;

	class Page extends \Controller{

		function __construct(){
			\CO::RE()->ARR('js');
			\CO::RE()->ARR('css');
		}

		function test(){
			$o = new \Application\Test\Model\Order();
			$o->qeueNewSort();
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

		function user($args = []){
			$this->model = new \Application\Test\Model\Page\User();
			$this->view = new \Application\Test\View\Page\User();

			if(isset(\CO::RE()->post['act']) && \CO::RE()->post['act'] === 'edit'){
				$this->userEdit();
			}
			
			$this->htmlOut( $this->view->get( $this->model->data() ) );
		}
		function userEdit(){
			$this->model->edit([
				'name' => \CO::RE()->post['name'],
				'email' => \CO::RE()->post['email'],
				'passwd' => \CO::RE()->post['passwd'],
				'passwdNew' => \CO::RE()->post['passwd_new'],
			]);
		}
	}