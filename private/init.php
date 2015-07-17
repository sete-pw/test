<?
	CO::RE()->errorReporting(E_PARSE | E_ERROR | E_WARNING);

	//------------- Библиотеки

	require_once(DIR_ROOT . 'libs/db.php');
	require_once(DIR_ROOT . 'init_db.php');

	require_once(DIR_ROOT . 'api/init_api.php');


	//------------- URL


	CO::RE()->PUSH('action', [
		'url' => '/^api\/.*/',
		'function' => function($name){
			
			echo 'This API';

			CO::RE()->end();
		}
	]);

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

	//-------------- 404

	CO::RE()->PUSH('action', [
		'url' => '/^.*/',
		'function' => function($name){
			echo $name['desc'] . '. Unknown page: ' . CO::RE()->url;
		},
		'desc' => '404'
	]);


	//-------------- Инициализация url

	CO::RE()->ACTION();

	

