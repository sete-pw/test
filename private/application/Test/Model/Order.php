<?
	namespace Application\Test\Model;
	use Application\Test\Controller\ApiConstants;
	class Order extends \ModelSql{
		protected $table = 'orders';
		
		function data(){
			return $this->VALUES();
		}

		function getList(){
			if (\CO::AUTH()->admin()) {
				$returnRequest = $this->QUERY("
SELECT id_order_set, order_id, orders.price, table_id, set_id
FROM orders
INNER JOIN order_sets ON orders.id_order = order_sets.order_id
INNER JOIN sets ON sets.id_set = order_sets.set_id
INNER JOIN tables ON sets.table_id = tables.id_table");

				return $returnRequest;
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