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




	// Действия
	CO::RE()->PUSH('action', [
		'url' => '/^login/',
		'function' => function(){
			
			$res = CO::AUTH()->login([
				'email' => CO::RE()->get['email'],
				'passwd' => CO::RE()->get['passwd']
			]);

			CO::RE()->redirect('/');

		}
	]);




	CO::RE()->PUSH('action', [
		'url' => '/^md5User/',
		'function' => function(){
			
			echo CO::AUTH()->getUserHash(CO::RE()->get['id'], CO::RE()->get['passwd']);

		}
	]);
	CO::RE()->PUSH('action', [
		'url' => '/^userAccess/',
		'function' => function(){
			
			var_dump(CO::AUTH()->unknown());
			var_dump(CO::AUTH()->user());
			var_dump(CO::AUTH()->admin());

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


