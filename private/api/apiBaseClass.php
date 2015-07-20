<?php
    class apiBaseClass{

        function createJson(){
            $JsonObject = json_decode('{}');
            return $JsonObject;
        }

        function fillJson($returnRequest, $JsonObject){
            foreach($returnRequest as $num=> $arr){
                $num = strtolower($num);
                $JsonObject->$num = json_decode('{}');
                foreach ($arr as $key=>$value){
                    $key = strtolower($key);
                    $JsonObject->$num->$key = $value;
                }
            }
            return $JsonObject;
        }
    }