<?
	namespace Application\Test\Model;

	use Application\Test\Controller\ApiConstants;

	class API extends \Model{

		function __call($name, $args){
			$class = "\\Application\\Test\\Model\\" . $args['0']['class'];
			if (\CO::AUTH()->admin() || \CO::AUTH()->user()){
			if(class_exists($class)){
				$apiClass = new $class();

				if (method_exists($apiClass, $name)){
					$query = $apiClass->$name(\CO::RE()->get);
					if (isset($query[ApiConstants::$ERROR_CODE]) || isset($query[ApiConstants::$STATUS])){
						$this->data = $query;
					}
					else{
						if (count($query)>0){
							$this->data[ApiConstants::$STATUS] = ApiConstants::$SUCCESS;
							$this->data[ApiConstants::$RESPONSE] = $query;
						}
						else {
							$this->data = [
								ApiConstants::$STATUS => ApiConstants::$SUCCESS,
								ApiConstants::$RESPONSE => []
							];
						}
					}
				}
				else{
					$this->data = [
						ApiConstants::$STATUS => ApiConstants::$ERROR,
						ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_NOT_FOUND_METHOD_STRING,
						ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_NOT_FOUND_METHOD_CODE
					];
				}
			}
			else{
				$this->data = [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_NOT_FOUND_CLASS_STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_NOT_FOUND_CLASS_CODE
				];
			}
		}
		else{
			$this->data = [
				ApiConstants::$STATUS => ApiConstants::$ERROR,
				ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
				ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
			];
		}
	}
}