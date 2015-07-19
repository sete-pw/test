<?php
class table extends apiBaseClass{
    function getList(){
        $retJson = $this->createJson();

        $returnRequest = CO::SQL()->query('SELECT id_table, position , price FROM tables');
        $retJson = $this->fillJson($returnRequest, $retJson);
        return $retJson;
    }

    function setList($JsonParams){

    }

}