<?
	abstract class Application{
		static private $one;
		static public function one(){
			if(is_null(self::$one)){
				$class = get_called_class();
				self::$one = new $class();
			}
			return self::$one;
		}
		final private function __construct(){}

		private $pid;
		private $dir;
		final public function pid(){
			return $this->pid;
		}
		final public function dir(){
			return $this->dir;
		}

		abstract function main();

		protected function onStop(){}

		final public function start($pid, $dir){
			if(is_null($this->pid)){
				$this->pid = $pid;
				$this->dir = $dir;
				$this->main();
			}
			return $this;
		}
		final public function stop(){
			$this->onStop();
			return $this;
		}



		final protected function inc($file){
			require_once($this->dir . $file);
		}
	}