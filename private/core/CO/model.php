<?
	trait coModel{
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
		 * Массив 'массивных' переменных проекта
		 * @var array
		 */
		private $__arrs;

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
			if(!$this->ISFIX($name)){
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
		 * Делает переменную 'массивной' переменную проекта
		 * @param string Название переменной
		 * @param mixed
		 */
		public function ARR($name, $array = null){
			if(!$this->ISFIX($name)){
				if(!is_null($array) && is_array($array)){
					$this->__vars[$name] = $array;
				}else{
					$this->__vars[$name] = [];
				}
				$this->__arrs[$name] = true;
			}
		}
		/**
		 * Проверяет, зафиксированна перменная или нет
		 * @param string Название переменной
		 * @return boolean
		 */
		public function ISFIX($name){
			return isset($this->__fixs[$name]);
		}
		/**
		 * Проверяет, 'массивная' перменная или нет
		 * @param string Название переменной
		 * @return boolean
		 */
		public function ISARR($name){
			return isset($this->__arrs[$name]);
		}
		/**
		 * Добавляет элемент в переменную (массив) проекта
		 * @param string Название переменной
		 * @param mixed Значение
		 * @param string Ключ элементы
		 */
		public function PUSH($name, $value, $key = null){
			if(!$this->ISFIX($name) && $this->ISARR($name)){
				if(isset($this->__vars[$name]) && is_array($this->__vars[$name]) || !isset($this->__vars[$name])){
					if(is_null($key)){
						$this->__vars[$name][] = $value;
					}else{
						$this->__vars[$name][$key] = $value;
					}
				}
			}
		}
		/**
		 * Ввозвращает значение элемента массива
		 * @param string Название переменной
		 * @param string Ключ в массиве
		 * @param boolean Удалить элемент
		 * @return mixed 
		 */
		public function POP($name, $key, $unset = false){
			if($this->ISARR($name)){
				if(isset($this->__vars[$name]) && is_array($this->__vars[$name])){
					$result = $this->__vars[$name][$key];
					if($unset && !$this->ISFIX($name)){
						unset($this->__vars[$name][$key]);
					}
					return $result;
				}
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
	}