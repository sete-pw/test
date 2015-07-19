<?php
    /*
     * ApiCore - реализует вызов api и его методы
     */
    require_once(DIR_ROOT . 'api/apiConstants.php');
    class ApiCore{

        private $apiFunctionName;
        private $apiFunctionParams; //Json в строковом представлении


        function __construct($apiFunctionName, $apiFunctionParams){
            $this->apiFunctionParams = stripcslashes($apiFunctionParams);
            $this->apiFunctionName = explode('.', $apiFunctionName);
        }

        function createJSON()
        {
            $returnJson = json_decode('{}');
            $responce = ApiConstants::$RESPONSE;
            $returnJson->$responce = json_decode('{}');
            return $returnJson;
        }

        //Подключение api
        static function getApiEngineByName($apiName) {
            require_once DIR_ROOT . 'api/apiBaseClass.php';
            require_once DIR_ROOT .'api/methods/'. $apiName .'.php';
            $apiClass = new $apiName();
            return $apiClass;
        }

        //Вызов метода по переданым параметрам из конструктора
        function callMethod(){
            $resultMethod = $this->createJSON();
            $apiName = stripcslashes($this->apiFunctionName[0]);

            if (file_exists(DIR_ROOT.'api/methods/'.$apiName.'.php')){
                $apiClass = ApiCore::getApiEngineByName($apiName);
                $apiReflection = new ReflectionClass($apiName);
                try{
                    $functionName = $this->apiFunctionName[1];
                    $apiReflection->getMethod($functionName); //Проверка метода
                    $jsonParams = json_decode($this->apiFunctionParams);
                    if ($jsonParams){
                        $response = ApiConstants::$RESPONSE;
                        $resultMethod->$response = $apiClass->$functionName($jsonParams);
                    }
                    else{
                        $resultMethod->errNum = ApiConstants::$ERROR_ENGINE_PARAMS;
                        $resultMethod->errStr = 'Error given params';
                    }
                }
                catch(Exception $ex) {
                    $resultMethod->errStr = $ex->getMessage();
                }
            }
            else{
                $resultMethod->errStr = 'Not found method';
                $resultMethod->errNum = ApiConstants::$ERROR_NOT_FOUND_METHOD;
                $resultMethod->REQUEST = $_REQUEST;
            }

            return json_encode($resultMethod);
        }
    }