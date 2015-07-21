<?
	namespace Application\Test\Model;

	class Table extends \ModelSql{
		protected $table = 'tables';
		
		function data(){
			return $this->VALUES();
		}

		function getList(){
			$ReturnRequest =  $this->QUERY('SELECT id_table, position, price FROM tables',[],'id_table');
			if (count($ReturnRequest) > 0) return $ReturnRequest; else return ['errMsg'=>'Not found record','errNum'=>3];
		}

		function setList($params){
			if (isset($params['table_id'])){
				$ReturnRequest = $this->QUERY("SELECT id_set, position
                                                FROM sets left join order_sets on sets.id_set = order_sets.set_id
                                                WHERE (state is null or state = ?) and table_id  = ?
                                                ",[
													['s','delete'],
													['i',$params['table_id']]],'id_set');
				if (count($ReturnRequest) > 0) return $ReturnRequest; else return ['errMsg'=>'Not found record','errNum'=>3];
			}
			else return ['errMsg' => 'Missing params', 'errNum' => 1];
		}
	}