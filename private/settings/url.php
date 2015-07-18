<?
	
	// API
	CO::RE()->PUSH('action', [
		'url' => '/^api\/.*/',
		'function' => function($name){
			
			require_once(DIR_ROOT . 'api/api.php');

			CO::RE()->end();
		}
	]);

	// Index
	CO::RE()->PUSH('action', [
		'url' => '/^$/',
		'function' => function(){

			include DIR_ROOT . 'www/index.php';
			
		}
	]);

	

	// ------- 404


	CO::RE()->PUSH('action', [
		'url' => '/^.*/',
		'function' => function($name){
			echo $name['desc'] . '. Unknown page: ' . CO::RE()->url;
		},
		'desc' => '404'
	]);


