<?
	namespace Application\Test\Model;

	use Application\Test\Controller\ApiConstants;

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
			if (\CO::AUTH()->admin()) {

				$set_a = new \Application\Test\Model\OrderSet();
				$set_b = new \Application\Test\Model\OrderSet();

				$set_a->findBy_id_order_set($params['order_set_id_a']);
				$set_b->findBy_id_order_set($params['order_set_id_b']);
				if (!isset($set_a->id_order_set) ||!isset($set_b->id_order_set)){
					return null;
				}

				$a = $set_a->sort_id;

				$set_a->sort_id = $set_b->sort_id;
				$set_a->UPDATE();
				$set_b->sort_id = $a;
				$set_b->UPDATE();

				return [
					ApiConstants::$STATUS => ApiConstants::$SUCCESS
				];
			}
			if (\CO::AUTH()->unknown() || \CO::AUTH()->user()) {
				return [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
				];
			}
		}


	}