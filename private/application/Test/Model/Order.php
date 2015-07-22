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
SELECT id_order_set, order_id, orders.price, table_id, set_id, user_id, users.name, CONCAT(tables.position,';',sets.position) as position
FROM orders
INNER JOIN order_sets ON orders.id_order = order_sets.order_id
INNER JOIN sets ON sets.id_set = order_sets.set_id
INNER JOIN tables ON sets.table_id = tables.id_table
INNER JOIN users on users.id_user = orders.user_id
ORDER BY sort_id desc");

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

		function complete($params){

			if (\CO::AUTH()->admin()) {

			}
			if (\CO::AUTH()->unknown() || \CO::AUTH()->user()) {
				return [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
				];
			}
		}

		/**
	     * Выполняет расстановку элементов по позициям в очереди
	     */
	    function qeueNewSort(){
	    	$new = $this->QUERY(
	            "SELECT
	                id_order_set,
	                user_id
	            from order_sets left join orders
	            on order_id = id_order
	            where
	                orders.state = 'bin'
	                and
	                order_sets.sort_id >= 0;
	        ");

			if($newCount = count($new)){
				$users = $this->QUERY(
					"SELECT
						user_id,
						count(id_order_set) as order_count
					from order_sets left join orders
					on order_id = id_order
					where
						orders.state = 'bin'
						and
						order_sets.sort_id >= 0
					order by user_id;
				");

				$qeue = [];
				foreach($users as $user){
					
				}
			}
		}
	}