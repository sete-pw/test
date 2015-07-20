<?php
    class apiBaseClass{

        function createJson(){
            $JsonObject = json_decode('{}');
            return $JsonObject;
        }

        function fillJson($returnRequest, $JsonObject){
            foreach($returnRequest as $key => $value){
                $key = strtolower($key);
                if (count($value)>1){
                    foreach ($value as $key1 => $value1) {
                        $JsonObject->$key->$key1 = $value1;
                    }
                }else $JsonObject->$key = $value;
            }
            return $JsonObject;
        }

    }