<?php
    /*
     * ApiCore - реализует вызов api и его методы
     */
    require_once(DIR_ROOT . 'api/apiConstants.php');
    class ApiCore{

        private $apiFunctionName;
        private $apiFunctionParams;


        function __construct($apiFunctionName, $apiFunctionParams){
            $this->apiFunctionParams = $apiFunctionParams;
            $this->apiFunctionName = $apiFunctionName;
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
            $apiName = stripcslashes($this->apiFunctionName['class']);
            $status = ApiConstants::$STATUS;
            echo '1';
            if (file_exists(DIR_ROOT.'api/methods/'.$apiName.'.php')){
                $apiClass = ApiCore::getApiEngineByName($apiName);
                $apiReflection = new ReflectionClass($apiName);
                echo '2';
                try{
                    $functionName = $this->apiFunctionName['method'];
                    $apiReflection->getMethod($functionName); //Проверка метода
                    $jsonParams = json_decode($this->apiFunctionParams);
                        if ($jsonParams){
                        $response = ApiConstants::$RESPONSE;
                        $resultMethod->$response = $apiClass->$functionName($jsonParams);
                        $resultMethod->$status = ApiConstants::$ERROR_NO;
                    }
                    else{
                        $resultMethod->$status = ApiConstants::$ERROR_ENGINE_PARAMS;
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