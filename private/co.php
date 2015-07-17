<?
	final class CO{
		/**
		Singltone
		*/
		static private $ST = false;
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
					if(isset($this->__redirect)){
						header('Location: ' . $this->__redirect);
					}
					foreach ($this->cookie as $key => $value) {
						if(!isset($_COOKIE[$key]) || $_COOKIE[$key] != $value){
							setcookie($value);
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

			$this->FIX('action', []);
			$this->FIX('url', trim(explode('?', $this->server['REQUEST_URI'])[0] , '/'));

			ob_start();
		}
		/**
		Model
		*/
		private $__vars;
		private $__fixs;
		public function __get($name){
			if(isset($this->__vars[$name])){
				return $this->__vars[$name];
			}
		}
		public function __set($name, $value){
			if(!isset($this->__fixs[$name])){
				$this->__vars[$name] = $value;
			}
		}
		public function __isset($name){
			return isset($this->__vars[$name]);
		}
		public function __call($name, $args){
			if(isset($this->__vars[$name])){
				call_user_func_array($this->__vars[$name], $args);
			}
		}
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
		public function ISFIX($name){
			return isset($this->__fixs[$name]);
		}
		public function PUSH($name, $value){
			if(isset($this->__vars[$name])){
				if(is_array($this->__vars[$name])){
					$this->__vars[$name][] = $value;
				}
			}else{
				$this->__vars[$name][] = $value;
			}
		}
		public function POP($name){
			if(isset($this->__vars[$name]) && is_array($this->__vars[$name])){
				$result = $this->__vars[$name];
				unset($this->__vars[$name]);
				return $this->__vars[$name];
			}
		}
		/**
		Static MODEL
		*/
		static private $__VARS = [];
		static public function __callStatic($name, $args){
			if(count($args)){
				self::$__VARS[$name] = $args[0];
			}
			return self::$__VARS[$name];
		}
		static public function REM($name){
			unset(self::$__VARS[$name]);
		}
		/**
		Methods
		*/
		public function errorReporting($levels = E_ALL){
			error_reporting($levels);
		}
		public function redirectNext($addr){
			if(!$this->ISFIX('__redirect')){
				$this->__callAllArray('onRedirectNext');
				$this->FIX('__redirect', $addr);
			}
		}
		public function redirect($addr){
			$this->__callAllArray('onRedirect');
			$this->redirectNext($addr);
			$this->end();
		}
		public function ACTION(){
			foreach ($this->action as $key => $action) {
				if(preg_match($action['url'], $this->url)){
					$function = $action['function'];
					unset($action['url']);
					unset($action['function']);
					call_user_func($function, $action);
					return;
				}
			}
		}
		/**
		Systems
		*/
		private function __callAllArray($name, $args = []){
			if(isset($this->__vars[$name]) && is_array($this->__vars[$name])){
				foreach ($this->__vars[$name] as $key => $function){
					call_user_func_array($function, $args);
				}
			}
		}
		public function end(){
			$this->__callAllArray('onEnd');
			ob_end_flush();
			die;
		}
	}