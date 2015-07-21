<?
	abstract class Controller{
		public $view;
		public $model;

		public function index($args = []){
			
		}
	}




	abstract class Model{
		public $data;
		public function data(){
			return $this->data;
		}
	}




	abstract class View{
		public $content;

		public function get($data){
			ob_start();
			$this->content($data);
			$this->content = ob_get_clean();

			return $this->content;
		}

		abstract protected function content($data);
	}