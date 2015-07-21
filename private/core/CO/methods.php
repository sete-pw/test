<?
	trait coMethods{
		public function header($name, $value){
			$this->__vars['header'][trim(strtolower($name))] = $value;
		}
		public function cookie($name, $value, $expire = 0){
			$this->__vars['newCookie'][trim(strtolower($name))] = [
				'value' => $value,
				'expire' => (int)$expire
			];
		}

		/**
		 * Выполняет перенаправление
		 * @param  string URL
		 */
		public function redirect($url){
			$this->__callAllArray('onRedirect');
			$this->header('location', $url);
			$this->systemStop();
		}
		/**
		User methods
		*/
		/**
		 * Устанавливает урвень оповещения об ошибках
		 * @param  const Error level
		 */
		public function errorReporting($levels = E_ALL){
			error_reporting($levels);
		}
		
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
	}