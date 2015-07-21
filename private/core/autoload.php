<?
	function __autoload($class){
		$space = explode('\\', $class);

		switch ($space[0]) {
			case 'Core':
				unset($space[0]);
				include DIR_CORE . implode('/', $space) . '.php';
				break;

			case 'Application':
				unset($space[0]);
				include DIR_APP . implode('/', $space) . '.php';
				break;
			
			default:
				include DIR_LIB . trim(implode('/', $space), '/') . '.php';
				break;
		}
	}