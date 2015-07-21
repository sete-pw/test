<?
	trait coProcessControl{
		private $pid;
		private $__process;

		function processStart($applicationClass){
			$applicationClass = 'Application\\' . $applicationClass . '\\' . $applicationClass;
			$this->appIncluded[$applicationClass] = 1;
			$this->__process[++$this->pid] = ['class' => $applicationClass, 'object' => $applicationClass::one()];
			return $this->__process[$this->pid]['object']->start($this->pid, DIR_APP . $applicationClass . '/');
		}
		function processStop($pid){
			$this->__process[$pid]['object']->stop();
			unset($this->__process[$pid]);
		}

		function processGet($pid){
			return $this->__process[$pid]['object'];
		}

		function systemStop(){
			$this->__callAllArray('onEnd');

			foreach ($this->__process as $pid => $process) {
				$this->processStop($pid);
			}

			foreach ($this->__vars['newCookie'] as $key => $value) {print_r($value);
				if($this->__vars['cookie'] !== $value['value']){
					setcookie($key, $value['value'], $value['expire']);
				}
			}
			foreach ($this->__vars['header'] as $key => $value) {
				header($key . ':' . trim($value));
			}

			ob_end_flush();
			die;
		}
	}