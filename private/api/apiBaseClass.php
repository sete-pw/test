<?php


    class apiBaseClass{

        function __construct(){

        }


        function createJson(){
            $JsonObject = json_decode('{}');
            return $JsonObject;
        }



    }