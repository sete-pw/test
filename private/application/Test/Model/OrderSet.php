<?
	namespace Application\Test\Model;

	use Application\Test\Controller\ApiConstants;

	class OrderSet extends \ModelSql{
		protected $table = 'order_sets';

		function data(){
			return $this->VALUES();
		}


		function swap($params){
			if (!isset($params['sort_id_a']) || !isset($params['sort_id_b'])){
				return [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
			}
			if (\CO::AUTH()->admin()) {
				$a = $params['sort_id_a'];
				$b = $params['sort_id_b'];

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

				/* // ORM это хорошо, но так будет работать быстрей ***********************

				$set_a = new \Application\Test\Model\OrderSet();
				$set_b = new \Application\Test\Model\OrderSet();

				$set_a->findBy_id_order_set($params['order_set_id_a']);
				$set_b->findBy_id_order_set($params['order_set_id_b']);

				$this->QUERY("
UPDATE order_sets
SET sort_id = sort_id +1
WHERE sort_id < ?
AND sort_id > ?
				",[
					['i',$set_a->sort_id],
					['i',$set_b->sort_id]
				]);
				$set_a->sort_id = $set_b->sort_id + 1;
				$set_a->UPDATE();

				return [
					ApiConstants::$STATUS => ApiConstants::$SUCCESS
				];

				*/
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