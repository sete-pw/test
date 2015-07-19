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




	/**
	 * �����������
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
	 * �����
	 */
	CO::RE()->PUSH('action', [
		'url' => '/^logout/',
		'function' => function(){
			
			CO::AUTH()->logout();

			CO::RE()->redirect('/');

		}
	]);



	// �������� ��� ��� �����
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


