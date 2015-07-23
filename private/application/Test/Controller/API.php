<?
	namespace Application\Test\Controller;

	class API extends \Controller{
		private $router;

		function __construct(){
			$this->model = new \Application\Test\Model\API();
			$this->view = new \Application\Test\View\Json();
		}

		function index($args = []){

			$this->model->$args['method']($args);
			return $this->view->get( $this->model->data() );

		}


}

	class ApiConstants{

		//Результат запроса в JSON (параметр)
		public static $RESULT_CODE = "result_code";

		//Ответ (параметр)
		public static $RESPONSE = "response";

		//Статус ответ
		public static $STATUS = "status";

		//Ошибок нет
		public static  $SUCCESS = 'success';


		public static  $ERROR = 'error';

		public static $ERROR_MESSAGE = 'errMsg';

		public static $ERROR_CODE = 'errNum';

		//Ошибка в параметрах запроса
		public static $ERROR_PARAMS_CODE = 1;
		public static $ERROR_PARAMS_STRING = 'Missing params';

		//Ошибка в подготовке запроса к базе
		public static $ERROR_STMP = 2;

		//Запись не найдена
		public static $ERROR_NOT_FOUND_RECORD_CODE = 3;
		public static $ERROR_NOT_FOUND_RECORD_STRING = 'Not found record';

		//Ошибка при запросе к базе
		public static $ERROR_REQUEST = 4;

		//Ошибка в декодировании параметров
		public static $ERROR_ENGINE_PARAMS = 5;

		//Не найден метод
		public static $ERROR_NOT_FOUND_METHOD_CODE = 6;
		public static $ERROR_NOT_FOUND_METHOD_STRING = 'Not found method';

		//Не найден class
		public static $ERROR_NOT_FOUND_CLASS_CODE = 7;
		public static $ERROR_NOT_FOUND_CLASS_STRING= 'Not found class';

		//Ошибка доступа
		public static $ERROR_AUTH_CODE = 8;
		public static $ERROR_AUTH__STRING = 'Not auth user';

		public static $ERROR_NOT_FOUND_BIN_CODE = 9;
		public static $ERROR_NOT_FOUND_BIN_STRING = 'Not found bin';

		public static $ERROR_BUSY_SET_CODE = 10;
		public static $ERROR_BUSY_SET_STRING = 'This set is busy';

		public static $ERROR_NOT_FOUND_SET_CODE = 11;
		public static $ERROR_NOT_FOUND_SET_STRING = 'Not found set';
}