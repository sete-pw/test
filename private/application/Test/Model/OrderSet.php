<?
	namespace Application\Test\Model;

	use Application\Test\Controller\ApiConstants;

	class OrderSet extends \ModelSql{
		protected $table = 'order_sets';

		function data(){
			return $this->VALUES();
		}


		function swap($params){
			if (!isset($params['sort_id_before']) || !isset($params['sort_id_after'])){
				return [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
			}
			if (\CO::AUTH()->admin()) {
				$a = $params['sort_id_before'];
				$b = $params['sort_id_after'];
				if($a == $b || ($a*$b) <= 0){
					return [
						ApiConstants::$STATUS => ApiConstants::$ERROR,
						ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
						ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE
					];
				}
				$args[] = ['i', $a > $b ? 1 : -1];
				$args[] = ['i', $a > $b ? $b : $a+1];
				$args[] = ['i', $a > $b ? $a-1 : $b];
				// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Нужна транзакция
				// Исключаем из списка A
				$this->QUERY(
					"UPDATE order_sets
					set
						sort_id = -1
					where
						sort_id = ?
					limit 1;
				", [
					['i', $a]
				]);
				// A перемещается в место после B
				$this->QUERY(
					"UPDATE order_sets
					set
						sort_id = sort_id + ?
					where
						sort_id >= ?
						and
						sort_id <= ?
						and
						sort_id > 0
				", $args);
				// Включаем в список A -> B
				$this->QUERY(
					"UPDATE order_sets
					set
						sort_id = ?
					where
						sort_id = -1
					limit 1;
				", [
					['i', $b]
				]);
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