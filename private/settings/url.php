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




	/**
	 * Авторизация
	 */
	CO::RE()->PUSH('action', [
		'url' => '/^login/',
		'function' => function(){
			
			CO::AUTH()->login([
				'email' => CO::RE()->post['email'],
				'passwd' => CO::RE()->post['passwd']
			]);

			CO::RE()->redirect('/');

		}
	]);



	// Получить хеш для юзера
	CO::RE()->PUSH('action', [
		'url' => '/^md5User/',
		'function' => function(){
			
			echo CO::AUTH()->getUserHash(CO::RE()->get['id'], CO::RE()->get['passwd']);

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


