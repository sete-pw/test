<?
	CO::AUTH(
		new \Security\Authorize(
			CO::SQL(),
			'039hg0whas9w8bp3wg',

			// Auth
			function($e, $args){
				if(
					isset(CO::RE()->cookie['authid'])
					and
					isset(CO::RE()->cookie['authsh'])
				){
					return $e->tryAuth(CO::RE()->cookie['authid'], CO::RE()->cookie['authsh']);
				}
				return false;
			},
			// Login
			function($e, $args){
				$user = $e->getUserByEmail($args['email']);

				if($user){
					$passwd = CO::AUTH()->getUserHash($user['id_user'], $args['passwd']);

					CO::RE()->PUSH('cookie', $user['id_user'], 'authid');
					CO::RE()->PUSH('cookie', $passwd, 'authsh');
				}
			},
			// Logout
			function($e, $args){
				CO::RE()->PUSH('cookie', '', 'authid');
				CO::RE()->PUSH('cookie', '', 'authsh');
			}
		)
	);

	CO::AUTH()->auth();
