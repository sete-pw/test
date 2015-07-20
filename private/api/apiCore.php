<?php
    /*
     * ApiCore - реализует вызов api и его методы
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
            if (file_exists(DIR_ROOT.'api/methods/'.$apiName.'.php')){
                $apiClass = ApiCore::getApiEngineByName($apiName);
                $apiReflection = new ReflectionClass($apiName);

                try{
                    $functionName = $this->apiFunctionName['method'];
                    $apiReflection->getMethod($functionName); //Проверка метода
                    $jsonData = json_decode($this->apiFunctionParams);
                    if ($jsonData){

                        $response = ApiConstants::$RESPONSE;
                        $res = $apiClass->$functionName($jsonData);
                        if  ($res == null){
                            $resultMethod->$status = ApiConstants::$ERROR_NOT_FOUND_RECORD;
                        }else{
                            if ($res->err == ApiConstants::$ERROR_PARAMS){
                                $resultMethod->$status = ApiConstants::$ERROR_PARAMS;
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

                    $resultMethod->errStr = $ex->getMessage();
                }
            }
            else{
                $resultMethod->errStr = 'Not found method';
                $resultMethod->$status = ApiConstants::$ERROR_NOT_FOUND_METHOD;
                $resultMethod->params = $this->apiFunctionParams;
            }
            return json_encode( iconv('cp1251','utf-8',$resultMethod), JSON_UNESCAPED_UNICODE);
        }
    }