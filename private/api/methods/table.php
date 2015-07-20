<?php
class table extends apiBaseClass{
    function getList(){
        $retJson = $this->createJson();

        $returnRequest = CO::SQL()->query('SELECT id_table, position, price FROM tables');
        if (empty($returnRequest)) return null;
        print_r($returnRequest);
        $retJson = $this->fillJson($returnRequest, $retJson);
        return $retJson;
    }

    function setList($Params){
        $retJson = $this->createJson();
        if(isset($Params->table_id)){
            $returnRequest = CO::SQL()->query('SELECT id_set, position FROM sets WHERE table_id =?',[['i',$Params->table_id]]);
            if (empty($returnRequest)) return null;
            $retJson = $this->fillJson($returnRequest, $retJson);
        }
        else{
            $retJson->err = ApiConstants::$ERROR_PARAMS;
        }
        return $retJson;
    }
}