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
		public static  $ERROR_NO = 0;

		//Ошибка в параметрах запроса
		public static $ERROR_PARAMS = 1;

		//Ошибка в подготовке запроса к базе
		public static $ERROR_STMP = 2;

		//Запись не найдена
		public static $ERROR_NOT_FOUND_RECORD = 3;

		//Ошибка при запросе к базе
		public static $ERROR_REQUEST = 4;

		//Ошибка в декодировании параметров
		public static $ERROR_ENGINE_PARAMS = 5;

		//Не найден метод
		public static $ERROR_NOT_FOUND_METHOD = 6;

		//Не найден class
		public static $ERROR_NOT_FOUND_CLASS = 7;

		//Ошибка доступа
		public static $ERROR_AUTH = 8;
}