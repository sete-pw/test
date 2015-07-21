<?
	abstract class Controller{
		public $view;
		public $model;

		abstract function index($args = []);
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
			$this->content = ob_end_flush();

			return $this->content;
		}

		abstract function content($data);
	}