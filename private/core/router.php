<?
	class Router{
		private $route;

		function push($route, $function){
			$this->route[] = [
				'route' => $route,
				'function' => $function
			];
		}

		function start($url){
			foreach ($this->route as $key => $action){
				if(preg_match($action['route'], $url, $res)){
					$function = $action['function'];
					unset($action['route']);
					unset($action['function']);
					call_user_func($function, $res);
					return;
				}
			}
		}
	}