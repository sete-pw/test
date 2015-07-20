<?php
class bin extends apiBaseClass{
    function get($params){
        $retJson = $this->createJson();

        if(isset($params->user_id)){
            //$returnRequest = CO::SQL()->query('SELECT ')
        }
        else{
            $retJson->err = ApiConstants::$ERROR_PARAMS;
        }

    }
}