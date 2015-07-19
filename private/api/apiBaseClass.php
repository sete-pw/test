<?php
    class apiBaseClass{

        function createJson(){
            $JsonObject = json_decode('{}');
            return $JsonObject;
        }

        function fillJson($returnRequest, $JsonObject){
            foreach($returnRequest as $key => $value){
                $key = strtolower($key);
                $JsonObject->$key = $value;
            }
            return $JsonObject;
        }

    }