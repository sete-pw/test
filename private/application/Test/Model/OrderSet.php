<?
	namespace Application\Test\Model;

	class OrderSet extends \ModelSql{
		protected $table = 'order_sets';

		function data(){
			return $this->VALUES();
		}


		function swap($params){
			if (!isset($params['order_set_id_a']) && !isset($params['order_set_id_b'])){
				return [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
			}
			if (\CO::AUTH()->user()) {
				$binUser = $this->QUERY("SELECT *
											FROM orders inner join order_sets on orders.id_order = order_sets.order_id where user_id = 2 and orders.state = 'bin'")
				$returnRequest

				return $returnRequest;
			}
			if (\CO::AUTH()->unknown()) {
				return [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
				];
			}
		}
	}