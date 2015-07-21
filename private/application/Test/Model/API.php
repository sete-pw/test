<?
	namespace Application\Test\Model;

	use Application\Test\Controller\ApiConstants;

	class API extends \Model{

		function __call($name, $args){
			$class = "\\Application\\Test\\Model\\" . $args['0']['class'];
			if(class_exists($class)){
				$apiClass = new $class();

				if (method_exists($apiClass, $name)){
					$query = $apiClass->$name(\CO::RE()->get);
					if (isset($query['errNum'])){
						$this->data = $query;
					}
					else{
						if (count($query)>0){
							$this->data['status'] = 'success';
							$this->data['response'] = $query;
						}
						else {
							$this->data = [
								'status' => 'error',
								'errMsg' => 'Not found record',
								'errNum' => ApiConstants::$ERROR_NOT_FOUND_RECORD
							];
						}
					}
				}
				else{
					$this->data = [
						'status' => 'error',
						'errMsg' => 'Not found method',
						'errNum' => ApiConstants::$ERROR_NOT_FOUND_METHOD
					];
				}
			}
			else{
				$this->data = [
					'status' => 'error',
					'errMsg' => 'Not found class',
					'errNum' => ApiConstants::$ERROR_NOT_FOUND_CLASS
				];
			}
		}
	}