<?php
    class apiBaseClass{

        function createJson(){
            $JsonObject = json_decode('{}');
            return $JsonObject;
        }

        function fillJson($returnRequest, $JsonObject){
            foreach($returnRequest as $num=> $arr){
                $num = strtolower($num);
                foreach ($arr as $key=>$value){
                    $key = strtolower($key);
                    echo mb_detect_encoding($value) . '<br>';
                    $JsonObject->$num->$key = $value;
                }
            }
            return $JsonObject;
        }
    }