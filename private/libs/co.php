<?
	/**
	 * Мини фреймворк CO.
	 * Реализует методы и свойства, позволяющие быстро проектировать небольшие проекты.
	 * @author Сергей Терехов
	*/
	final class CO{
		/**
		Singltone
		*/
		static private $ST = false;
		/**
		 * Основной метод Singletone.
		 * @return CO
		 */
		static public function RE(){
			if(false === (self::$ST instanceof self)){
				self::$ST = new self();
			}
			return self::$ST;
		}
		/**
		Construct
		*/
		public function __construct(){
			$this->FIX('onEnd', [
				function(){
					foreach ($this->header as $key => $value) {
						header($key . ': ' . $value);
					}
					foreach ($this->cookie as $key => $value) {
						if(!isset($_COOKIE[$key]) || $_COOKIE[$key] != $value){
							setcookie($key, $value);
						}
					}
				}
			]);
			$this->FIX('onStart', []);
			$this->FIX('onRedirect', []);
			$this->FIX('onRedirectNext', []);

			$this->FIX('server', $_SERVER);
			$this->FIX('post', $_POST);
			$this->FIX('get', $_GET);
			$this->FIX('request', $_REQUEST);
			$this->FIX('files', $_FILES);
			$this->FIX('cookie', $_COOKIE);

			$this->FIX('header', []);

			$this->FIX('action', []);
			$this->FIX('url', trim(explode('?', $this->server['REQUEST_URI'])[0] , '/'));

			ob_start();
		}
		/**
		Model
		*/
		/**
		 * Массив переменных проекта
		 * @var array
		 */
		private $__vars;
		/**
		 * Массив блокировок переменных проекта
		 * @var array
		 */
		private $__fixs;

		/**
		 * Возврящает переменную проекта
		 * @param  string Название переменной
		 * @return mixed
		 */
		public function __get($name){
			if(isset($this->__vars[$name])){
				return $this->__vars[$name];
			}
		}
		/**
		 * Устанавливает значение переменной проекта
		 * @param string Название переменной
		 * @param mixed Значение переменной
		 */
		public function __set($name, $value){
			if(!isset($this->__fixs[$name])){
				$this->__vars[$name] = $value;
			}
		}
		/**
		 * Проверяет доступность переменной проекта
		 * @param  string Название переменной
		 * @return boolean
		 */
		public function __isset($name){
			return isset($this->__vars[$name]);
		}
		/**
		 * Вызывает функцию из переменной проекта
		 * @param  string Название переменной
		 * @param  array Аргументы функции
		 * @return mixed
		 */
		public function __call($name, $args){
			if(isset($this->__vars[$name])){
				return call_user_func_array($this->__vars[$name], $args);
			}
		}
		/**
		 * Фиксирует переменную проекта
		 * @param string Название переменной
		 * @param mixed
		 */
		public function FIX($name, $value = null){
			if(!isset($this->__fixs[$name])){
				if(!is_null($value)){
					$this->__vars[$name] = $value;
				}else{
					if(!isset($this->__vars[$name])){
						return;
					}
				}
				$this->__fixs[$name] = true;
			}
		}
		/**
		 * Проверяет, зафиксированна перменная или нет
		 * @param string Название переменной
		 */
		public function ISFIX($name){
			return isset($this->__fixs[$name]);
		}
		/**
		 * Добавляет элемент в переменную (массив) проекта
		 * @param string Название переменной
		 * @param mixed Значение
		 * @param string Ключ элементы
		 */
		public function PUSH($name, $value, $key = null){
			if(isset($this->__vars[$name]) && is_array($this->__vars[$name]) || !isset($this->__vars[$name])){
				if(is_null($key)){
					$this->__vars[$name][] = $value;
				}else{
					$this->__vars[$name][$key] = $value;
				}
			}
		}
		/**
		 * Удаляет из переменной (массива) элемент, возвращая его значение
		 * @param string Название переменной
		 * @param string Ключ в массиве
		 * @return mixed 
		 */
		public function POP($name, $key){
			if(isset($this->__vars[$name]) && is_array($this->__vars[$name])){
				$result = $this->__vars[$name][$key];
				unset($this->__vars[$name][$key]);
				return $result;
			}
		}
		/**
		Static MODEL
		*/
		/**
		 * Массив статичных переменных проекта
		 * @var array
		 */
		static private $__VARS = [];
		/**
		 * Возвращает или задает значение статичной переменной
		 * @param  string Название переменной
		 * @param  array Аргументы функции (первый параметр - значение переменной)
		 * @return mixed
		 */
		static public function __callStatic($name, $args){
			if(count($args)){
				self::$__VARS[$name] = $args[0];
			}
			return self::$__VARS[$name];
		}
		/**
		 * Удаляет из памяти статичную переменную
		 * @param string Название переменной
		 */
		static public function REM($name){
			unset(self::$__VARS[$name]);
		}
		/**
		Methods
		*/
		/**
		 * Устанавливает урвень оповещения об ошибках
		 * @param  const Error level
		 */
		public function errorReporting($levels = E_ALL){
			error_reporting($levels);
		}
		/**
		 * Выполняет перенаправление
		 * @param  string URL
		 */
		public function redirect($addr = null){
			$this->__callAllArray('onRedirect');
			if(!is_null($addr)){
				$this->__vars['header']['location'] = $addr;
			}
			$this->end();
		}
		/**
		 * Выполняет вызов соответствующего действия для текущего URL.
		 * Выполняется только один раз
		 */
		public function ACTION(){
			if(!$this->ISFIX('__action')){
				foreach ($this->action as $key => $action) {
					if(preg_match($action['url'], $this->url)){
						$function = $action['function'];
						unset($action['url']);
						unset($action['function']);
						call_user_func($function, $action);
						return;
					}
				}
				$this->FIX('__action', true);
			}
		}
		/**
		Systems
		*/
		/**
		 * Выполняет вызов функций из очереди в переменной проекта
		 * @param  string Название переменной
		 * @param  array Аргументы
		 */
		private function __callAllArray($name, $args = []){
			if(isset($this->__vars[$name]) && is_array($this->__vars[$name])){
				foreach ($this->__vars[$name] as $key => $function){
					call_user_func_array($function, $args);
				}
			}
		}
		/**
		 * Выполняет остановку скрипта.
		 */
		public function end(){
			if(!$this->ISFIX('__end')){
				$this->FIX('__end', true);
				$this->__callAllArray('onEnd');
				ob_end_flush();
				die;
			}
		}
	}