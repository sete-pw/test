<?
	namespace Application\Test\Model;

	class Set extends \ModelSql{
		protected $table = 'sets';
		
		function data(){
			return $this->VALUES();
		}

		function getList(){
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