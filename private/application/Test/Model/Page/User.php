<?
	namespace Application\Test\Model\Page;

	class User extends \Model{
		function __construct(){
			$this->data['accept'] = \CO::AUTH()->user();
		}

		function edit($vals){
			$error = true;
			$needAuth = false;

			if(isset($vals['name'])){
				\CO::AUTH()->who()->name = substr(strip_tags($vals['name']), 0, 255);
				$error = false;
			}
			if(
				isset($vals['passwd'])
				&&
				isset($vals['passwdNew'])
			){
				if(
					\CO::AUTH()->who()->passwd
					===
					\CO::AUTH()->getUserHash(\CO::AUTH()->who()->ID(), $vals['passwd'])
				){
					$vals['passwd'] = trim(strip_tags($vals['passwd']));
					$vals['passwdNew'] = trim(strip_tags($vals['passwdNew']));

					if($vals['passwd'] != $vals['passwdNew']){
						\CO::AUTH()->who()->passwd = \CO::AUTH()->getUserHash(\CO::AUTH()->who()->ID(), $vals['passwdNew']);
						$needAuth = true;
						$error = false;
					}
				}
			}

			if(!$error){
				\CO::AUTH()->who()->UPDATE();
				if($needAuth){
					\CO::AUTH()->auth(\CO::AUTH()->who());
				}

				\CO::RE()->redirect('/user');
			}
		}
	}