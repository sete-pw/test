<?
	namespace Application\Test\Model;

	class API extends \Model{

		function __call($name, $args){
			$this->data = [
				'status'=>'error',
				'code'=>404,
				'api' => $args
			];
		}
	}