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
                    $JsonObject->$num->$key = iconv('windows-1251', 'UTF-8',$value);
                }
            }
            return $JsonObject;
        }
    }