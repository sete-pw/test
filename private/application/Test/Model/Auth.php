<?
	namespace Application\Test\Model;

	class Auth extends \Model{
		private $salt;

		private $user;
		private $level;

		function __construct($salt){
			$this->salt = $salt;

			$this->level = 0;
			$this->user = new \Application\Test\Model\User();

			if(false !== $this->user->findBy_id_user((int)\CO::RE()->cookie['authid'])){
				if($this->user->passwd === \CO::RE()->cookie['authsh']){
					$this->level = $this->user->id_user != 1 ? 1 : 2;
				}
			}
		}

		function login($email, $passwd){
			$user = new \Application\Test\Model\User();

			if(
				false !== $user->findBy_email($email)
				&&
				$user->passwd === $this->getUserHash($user->ID(), $passwd)
			){
				\CO::RE()->cookie('authid', $user->id_user);
				\CO::RE()->cookie('authsh', $user->passwd);
			}
		}
		function logout(){
			\CO::RE()->cookie('authid', '');
			\CO::RE()->cookie('authsh', '');

			unset($this->user);
			$this->level = 0;
			$this->user = new \Application\Test\Model\User();
		}

		/**
		 * Возвращает запись пользователя в базе
		 * @return array
		 */
		public function who(){
			return $this->user;
		}
		/**
		 * Возвращает Хеш-пароль
		 * @param  number id пользователя
		 * @param  string Пароль
		 * @return string
		 */
		public function getUserHash($id, $passwd){
			return md5($this->salt . '<id:' . $id . 'passwd:' . $passwd . '>end');
		}
		/**
		 * Является ли пользователь ПОСЕТИТЕЛЕМ
		 * @return boolean
		 */
		public function unknown(){
			return ($this->level <= 0);
		}
		/**
		 * Является ли пользователь ЗАРЕГИСТРИРОВАННЫМ
		 * @return boolean
		 */
		public function user(){
			return ($this->level >= 1);
		}
		/**
		 * Является ли пользователь АДМИНИСТРАТОРОМ
		 * @return boolean
		 */
		public function admin(){
			return ($this->level >= 2);
		}

		function data(){}
	}