<?php
    /*
     * ApiCore - ��������� ����� api � ��� ������
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
            return $returnJson;
        }

        //����������� api
        static function getApiEngineByName($apiName) {
            require_once DIR_ROOT . 'api/apiBaseClass.php';
            require_once DIR_ROOT .'api/methods/'. $apiName .'.php';
            $apiClass = new $apiName();
            return $apiClass;
        }

        //����� ������ �� ��������� ���������� �� ������������
        function callMethod(){
            $resultMethod = $this->createJSON();
            $apiName = stripcslashes($this->apiFunctionName['class']);
            $status = ApiConstants::$STATUS;
            if (file_exists(DIR_ROOT.'api/methods/'.$apiName.'.php')){
                $apiClass = ApiCore::getApiEngineByName($apiName);
                $apiReflection = new ReflectionClass($apiName);

                try{
                    $functionName = $this->apiFunctionName['method'];
                    $apiReflection->getMethod($functionName); //�������� ������
                        $response = ApiConstants::$RESPONSE;
                        $res = $apiClass->$functionName($this->apiFunctionParams);
                        if  ($res == null){
                            $resultMethod->$status = ApiConstants::$ERROR_NOT_FOUND_RECORD;
                        }else{
                            if ($res->err == ApiConstants::$ERROR_PARAMS){
                                $resultMethod->$status = ApiConstants::$ERROR_PARAMS;
                                $resultMethod->params = $this->apiFunctionParams;
                            }
                            else{
                                $resultMethod->$response = $res;
                                $resultMethod->$status = ApiConstants::$ERROR_NO;
                            }

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