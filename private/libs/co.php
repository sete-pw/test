<?
	/**
	 * ���� ��������� CO.
	 * ��������� ������ � ��������, ����������� ������ ������������� ��������� �������.
	 * @author ������ �������
	*/
	final class CO{
		/**
		Singltone
		*/
		static private $ST = false;
		/**
		 * �������� ����� Singletone.
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
		 * ������ ���������� �������
		 * @var array
		 */
		private $__vars;
		/**
		 * ������ ���������� ���������� �������
		 * @var array
		 */
		private $__fixs;

		/**
		 * ���������� ���������� �������
		 * @param  string �������� ����������
		 * @return mixed
		 */
		public function __get($name){
			if(isset($this->__vars[$name])){
				return $this->__vars[$name];
			}
		}
		/**
		 * ������������� �������� ���������� �������
		 * @param string �������� ����������
		 * @param mixed �������� ����������
		 */
		public function __set($name, $value){
			if(!isset($this->__fixs[$name])){
				$this->__vars[$name] = $value;
			}
		}
		/**
		 * ��������� ����������� ���������� �������
		 * @param  string �������� ����������
		 * @return boolean
		 */
		public function __isset($name){
			return isset($this->__vars[$name]);
		}
		/**
		 * �������� ������� �� ���������� �������
		 * @param  string �������� ����������
		 * @param  array ��������� �������
		 * @return mixed
		 */
		public function __call($name, $args){
			if(isset($this->__vars[$name])){
				return call_user_func_array($this->__vars[$name], $args);
			}
		}
		/**
		 * ��������� ���������� �������
		 * @param string �������� ����������
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
		 * ���������, �������������� ��������� ��� ���
		 * @param string �������� ����������
		 */
		public function ISFIX($name){
			return isset($this->__fixs[$name]);
		}
		/**
		 * ��������� ������� � ���������� (������) �������
		 * @param string �������� ����������
		 * @param mixed ��������
		 * @param string ���� ��������
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
		 * ������� �� ���������� (�������) �������, ��������� ��� ��������
		 * @param string �������� ����������
		 * @param string ���� � �������
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
		 * ������ ��������� ���������� �������
		 * @var array
		 */
		static private $__VARS = [];
		/**
		 * ���������� ��� ������ �������� ��������� ����������
		 * @param  string �������� ����������
		 * @param  array ��������� ������� (������ �������� - �������� ����������)
		 * @return mixed
		 */
		static public function __callStatic($name, $args){
			if(count($args)){
				self::$__VARS[$name] = $args[0];
			}
			return self::$__VARS[$name];
		}
		/**
		 * ������� �� ������ ��������� ����������
		 * @param string �������� ����������
		 */
		static public function REM($name){
			unset(self::$__VARS[$name]);
		}
		/**
		Methods
		*/
		/**
		 * ������������� ������ ���������� �� �������
		 * @param  const Error level
		 */
		public function errorReporting($levels = E_ALL){
			error_reporting($levels);
		}
		/**
		 * ��������� ���������������
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
		 * ��������� ����� ���������������� �������� ��� �������� URL.
		 * ����������� ������ ���� ���
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
		 * ��������� ����� ������� �� ������� � ���������� �������
		 * @param  string �������� ����������
		 * @param  array ���������
		 */
		private function __callAllArray($name, $args = []){
			if(isset($this->__vars[$name]) && is_array($this->__vars[$name])){
				foreach ($this->__vars[$name] as $key => $function){
					call_user_func_array($function, $args);
				}
			}
		}
		/**
		 * ��������� ��������� �������.
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