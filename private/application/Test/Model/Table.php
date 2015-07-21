<?
	namespace Application\Test\Model;

	use Application\Test\Controller\ApiConstants;

	class Table extends \ModelSql{
		protected $table = 'tables';
		
		function data(){
			return $this->VALUES();
		}

		function getList(){
			$ReturnRequest =  $this->QUERY('SELECT id_table, position, price FROM tables',[],'id_table');
			return $ReturnRequest;
		}

		function getSetList($params){
			if (isset($params['table_id'])){
				$ReturnRequest = $this->QUERY("SELECT id_set, position
                                                FROM sets left join order_sets on sets.id_set = order_sets.set_id
                                                WHERE (state is null or state = ?) and table_id  = ?
                                                ",[
													['s','delete'],
													['i',$params['table_id']]]);
				return $ReturnRequest;
			}
			else return [
				ApiConstants::$STATUS => ApiConstants::$ERROR,
				ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
				ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
		}
	}