<?
	namespace Application\Test\Model;

	use Application\Test\Controller\ApiConstants;

	class Table extends \ModelSql{
		protected $table = 'tables';
		
		function data(){
			return $this->VALUES();
		}

		public function getList($params){
			$ReturnRequest =  $this->QUERY(
"SELECT
id_table, tables.position, price
FROM tables INNER JOIN sets ON tables.id_table = sets.table_id
WHERE id_set NOT
IN (
SELECT set_id
FROM order_sets
WHERE state IN (
'add', 'pay'
)
)",[],'id_table');
			return [
				ApiConstants::$STATUS =>ApiConstants::$SUCCESS,
				ApiConstants::$RESPONSE => $ReturnRequest
			];
		}


	}