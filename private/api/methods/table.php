<?php
class table extends apiBaseClass{
    function getList(){
        $retJson = $this->createJson();

        $returnRequest = CO::SQL()->query('SELECT * FROM tables');
        if (empty($returnRequest)) return null;
        $retJson = $this->fillJson($returnRequest, $retJson);
        return $retJson;
    }

    function setList($Params){
        $retJson = $this->createJson();
        echo '123';
        $returnRequest = CO::SQL()->query('SELECT id_set, position FROM sets WHERE table_id =?',[['i',$Params['table_id']]]);
        if (empty($returnRequest)) return null;
        $retJson = $this->fillJson($returnRequest, $retJson);
        return $retJson;
    }

}