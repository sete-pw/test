<?php
class table extends apiBaseClass{
    function getList(){
        $retJson = $this->createJson();

        $returnRequest = CO::SQL()->query('SELECT * FROM tables');
        $retJson = $this->fillJson($returnRequest, $retJson);
        if (isset($retJson['0'])) return $retJson;
        else return null;
    }

    function setList($JsonParams){

    }

}