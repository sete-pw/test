<?php
class table extends apiBaseClass{
    function getList(){
        $retJson = $this->createJson();

        $returnRequest = CO::SQL()->query('SELECT id_table, position , price FROM tables');
        $retJson->return = $this->fillJson($returnRequest, $retJson);
        print_r($retJson);
    }

    function setList($JsonParams){

    }

}