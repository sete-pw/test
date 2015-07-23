<?
	namespace Application\Test\Model;
	use Application\Test\Controller\ApiConstants;
	class Order extends \ModelSql{
		protected $table = 'orders';
		
		function data(){
			return $this->VALUES();
		}

		/**
		 * Получает список оплаченных заказов
		 */
		function getList(){
			if (\CO::AUTH()->admin()) {
				$returnRequest = $this->QUERY(
"SELECT id_order_set, order_id, orders.price, table_id, set_id, user_id, users.name, CONCAT(tables.position,';',sets.position) as position
FROM orders
INNER JOIN order_sets ON orders.id_order = order_sets.order_id
INNER JOIN sets ON sets.id_set = order_sets.set_id
INNER JOIN tables ON sets.table_id = tables.id_table
INNER JOIN users on users.id_user = orders.user_id
WHERE order_sets.state = 'pay'
ORDER BY sort_id");

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

		/**
		 * Закрывает заказ
		 * @param int Id заказа
		 * @return status
		 */
		function complete($params){
			if (!isset($params['id_order_set'])){
				return [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
			}
			if (\CO::AUTH()->admin()) {
				$order = new \Application\Test\Model\OrderSet();
				$orderId = $order->findBy_id_order_set($params['id_order_set']);

				if ($orderId instanceof $order){
					$orderId->state = 'delete';
					$orderId->UPDATE();
					return [
						ApiConstants::$STATUS => ApiConstants::$SUCCESS
					];
				}else{
					return null;
				}

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
	                orders.state = 'pay'
	                and
	                order_sets.sort_id = 0;
	        ");

			if($newCount = count($new)){
				$user = $this->QUERY(
					"SELECT
						user_id,
						count(id_order_set) as order_count
					from order_sets left join orders
					on order_id = id_order
					where
						orders.state = 'pay'
						and
						order_sets.sort_id = 0
					group by user_id
					order by order_count desc;
				");

				$qeue = [];

				if(count($user) > 1){
					// Переносим первого пользователя (максимум)
					foreach ($new as $key => $value){
						if($value['user_id'] == $user[0]['user_id']){
							$qeue[] = $value;
							unset($new[$key]);
						}
					}
					// Вполняем внедрение остальных пользователе
					for($i = 1; $i < count($user); $i++){
						// Вычисляем шаг
						$step = count($qeue) / $user[$i]['order_count'];
						$pos = 1;
						// внедряем записи
						foreach ($new as $key => $value){
							if($value['user_id'] == $user[$i]['user_id']){
								array_splice($qeue, (int)$pos, 0, [$value]);
								$pos += $step+1;
								unset($new[$key]);
							}
						}
					}
				}else{
					$qeue = $new;
				}

				// Делаем prepare
				$this->QUERY(
					["UPDATE order_sets
					set
						sort_id = 1 + (select * from (select max(sort_id) from order_sets) a)
					where
						id_order_set = ?
					limit 1;
				"], [
					['s', 0]
				]);

				// Выполняем для всех новых записей
				foreach ($qeue as $e){
					$this->QUERY(null, [
						$e['id_order_set']
					]);
				}

				//print_r($qeue);
			}
		}



		function edit($params){
			if (!isset($params['id_order_set']) || !isset($params['id_set'])){
				return [
					ApiConstants::$STATUS => ApiConstants::$ERROR,
					ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
					ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
			}
			if (\CO::AUTH()->admin()) {
				$order = new \Application\Test\Model\OrderSet();
				$orderId = $order->findBy_id_order_set($params['id_order_set']);
				$order_busy = new \Application\Test\Model\OrderSet();
				$order_busy

				if ($orderId instanceof $order){

				}
				else{
					return null;
				}
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