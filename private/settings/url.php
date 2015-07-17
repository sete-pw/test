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
			CO::RE()->name = 'Test project';
			CO::RE()->hello = function(){
				echo 'Hello, this is ' . CO::RE()->name . '!';
			};
			CO::RE()->hello();
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


