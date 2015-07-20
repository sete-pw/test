<?php
class table extends apiBaseClass{
    function getList(){
        $retJson = $this->createJson();

        $returnRequest = CO::SQL()->query('SELECT id_table, position, price FROM tables');
        if (empty($returnRequest)) return null;
        $retJson = $this->fillJson($returnRequest, $retJson);
        return $retJson;
    }

    function setList($Params){
        $retJson = $this->createJson();
        if(isset($Params->table_id)){
            $returnRequest = CO::SQL()->query("SELECT id_set, position
                                                FROM sets left join order_sets on sets.id_set = order_sets.set_id
                                                WHERE (state is null or state = ?) and table_id  = ?
                                                ",[['s','delete'],['i',$Params->table_id]]);
            if (empty($returnRequest)) return null;
            $retJson = $this->fillJson($returnRequest, $retJson);
        }
        else{
            $status =ApiConstants::$STATUS;
            $retJson->$status = ApiConstants::$ERROR_PARAMS;
        }
        return $retJson;
    }
}