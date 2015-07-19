<?php
class table extends apiBaseClass{
    function getList(){
        $retJson = $this->createJson();

        $returnRequest = CO::SQL()->query('SELECT * FROM tables');
        $retJson = $this->fillJson($returnRequest, $retJson);
        echo count($retJson);
        return $retJson;
    }

    function setList($JsonParams){

    }

}