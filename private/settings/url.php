<?
	
	// API
	CO::RE()->PUSH('action', [
		'url' => '/^api\/.*/',
		'function' => function($name){
			
			$api = CO::RE()->url;
			$api = explode('/', $api)[1];
			$api = explode('.', $api);
			CO::RE()->FIX('api', ['class' => $api[0], 'method' => $api[1]]);

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
	// Shop
	CO::RE()->PUSH('action', [
		'url' => '/^shop$/',
		'function' => function(){

			include DIR_ROOT . 'www/shop.php';

		}
	]);
	// user
	CO::RE()->PUSH('action', [
		'url' => '/^user$/',
		'function' => function(){

			include DIR_ROOT . 'www/user.php';

		}
	]);
	// admin
	CO::RE()->PUSH('action', [
		'url' => '/^admin$/',
		'function' => function(){

			include DIR_ROOT . 'www/admin.php';

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
	/**
	 * Выход
	 */
	CO::RE()->PUSH('action', [
		'url' => '/^logout/',
		'function' => function(){
			
			CO::AUTH()->logout();

			CO::RE()->redirect('/');

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


