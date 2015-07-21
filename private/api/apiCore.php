<?php
    /*
     * ApiCore - СЂРµР°Р»РёР·СѓРµС‚ РІС‹Р·РѕРІ api Рё РµРіРѕ РјРµС‚РѕРґС‹
     */
    require_once(DIR_ROOT . 'api/apiConstants.php');
    class ApiCore{

        private $apiFunctionName;
        private $apiFunctionParams;


        function __construct($apiFunctionName, $apiFunctionParams){
            $this->apiFunctionParams = stripcslashes($apiFunctionParams);
            $this->apiFunctionName = $apiFunctionName;
        }

        function createJSON()
        {
            $returnJson = json_decode('{}');
            $responce = ApiConstants::$RESPONSE;
            return $returnJson;
        }

        //РџРѕРґРєР»СЋС‡РµРЅРёРµ api
        static function getApiEngineByName($apiName) {
            require_once DIR_ROOT . 'api/apiBaseClass.php';
            require_once DIR_ROOT .'api/methods/'. $apiName .'.php';
            $apiClass = new $apiName();
            return $apiClass;
        }

        //Р’С‹Р·РѕРІ РјРµС‚РѕРґР° РїРѕ РїРµСЂРµРґР°РЅС‹Рј РїР°СЂР°РјРµС‚СЂР°Рј РёР· РєРѕРЅСЃС‚СЂСѓРєС‚РѕСЂР°
        function callMethod(){
            $resultMethod = $this->createJSON();
            $apiName = stripcslashes($this->apiFunctionName['class']);
            $status = ApiConstants::$STATUS;
            if (file_exists(DIR_ROOT.'api/methods/'.$apiName.'.php')){
                $apiClass = ApiCore::getApiEngineByName($apiName);
                $apiReflection = new ReflectionClass($apiName);


                try{
                    $functionName = $this->apiFunctionName['method'];
                    $apiReflection->getMethod($functionName); //РџСЂРѕРІРµСЂРєР° РјРµС‚РѕРґР°
                    $jsonData = json_decode($this->apiFunctionParams);
                    if ($jsonData){

                        $response = ApiConstants::$RESPONSE;
                        $res = $apiClass->$functionName($jsonData);
                        if  ($res == null){
                            $resultMethod->$status = ApiConstants::$ERROR_NOT_FOUND_RECORD;
                        }else{
                            if (isset($res->$status)){
                                $resultMethod = $res;
                                $resultMethod->params = json_encode($jsonData);
                            }
                            else{
                                $resultMethod->$response = $res;
                                $resultMethod->$status = ApiConstants::$ERROR_NO;
                            }

                        }
                    }else{
                        $resultMethod->$status = ApiConstants::$ERROR_ENGINE_PARAMS;
                    }
                }
                catch(Exception $ex) {
                    $resultMethod->$status = ApiConstants::$ERROR_STMP;
                    $resultMethod->params = $this->apiFunctionParams;
                }
            }
            else {
                $resultMethod->$status = ApiConstants::$ERROR_NOT_FOUND_METHOD;
                $resultMethod->params = $this->apiFunctionParams;
            }
            return json_encode($resultMethod, JSON_UNESCAPED_UNICODE);
        }
    }