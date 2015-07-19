<?php
class table extends apiBaseClass{
    function getList(){
        $retJson = $this->createJson();

        $returnRequest = CO::SQL()->query('SELECT * FROM users');
        $retJson = $this->fillJson($returnRequest, $retJson);
        
        return $retJson;
    }

    function setList($JsonParams){

    }

}