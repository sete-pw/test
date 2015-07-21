<?
	namespace Application\Test\Model;

	class API extends \Model{

		function __call($name, $args){
			$class = "\\Application\\Test\\Model\\" . $args['0']['class'];
			try{
				$apiClass = new $class();

				if (method_exists($apiClass, $name)){
					$query = $apiClass->$name(\CO::RE()->get);
					if (isset($this->data['errNum'])){
						$this->data['status'] = 'error';
						$this->data['response'] = $query;
					}
					else{
						$this->data['status'] = 'success';
						$this->data['response'] = $query;
					}
				}
				else{
					$this->data = [
						'status' => 'error',
						'errMsg' => 'Not found method',
						'errNum' => 6
					];
				}
			}
			catch(\Exception $exp){
				$this->data = [
					'status' => 'error',
					'errMsg' => 'Not found class',
					'errNum' => 7
				];
			}
		}
	}