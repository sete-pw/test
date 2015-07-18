<?
	namespace Security;

	/**
	 * Авторизация пользователя
	 * @author Сергей Терехов
	 */
	class Authorize{

		private $sql;
		private $salt;

		private $cbAuth;
		private $cbLogin;
		private $cbLogout;

		private $level;
		private $user;

		/**
		 * Конструктор класса. Задает базовые переменные и события
		 * @param \SQL\DATA Подключение к базе данных
		 * @param string Соль для пароля
		 * @param function Событие авторизации на сервере
		 * @param function Событие ДЕавторизации на сервере
		 */
		public function __construct($sql, $salt, $cbAuth, $cbLogin, $cbLogout){
			$this->level = -1;

			if($sql instanceof \SQL\DATA && $sql->isConnect()){
				$this->sql = $sql;
				$this->salt = $salt;

				$this->cbAuth = $cbAuth;
				$this->cbLogin = $cbLogin;
				$this->cbLogout = $cbLogout;
			}
		}

		/**
		 * Выполняет авторизацию, возвращая результат авторизации. Используется в событиях
		 * @param  number id пользователя
		 * @param  string Хеш-пароль пользователя
		 * @return boolean
		 */
		public function tryAuth($userId, $userHash){
			if($this->level == -1){
				$this->level = 0;

				$user = $this->sql->query("
select *
from users
where
	id_user = ?
	and
	passwd = ?
limit 1;
				", [
					['i', $userId],
					['s', $userHash]
				]);

				if(count($user)){
					$this->user = $user[0];
					if($this->user['id_user'] == 1){
						$this->level = 2;
					}else{
						$this->level = 1;
					}

					return true;
				}
			}
			return false;
		}

		/**
		 * Выполнить алгоритм авторизации клиента НА СЕРВЕРЕ
		 * @param  mixed Дополнительный массив аргументов
		 * @return mixed
		 */
		public function auth($args = []){
			return call_user_func_array($this->cbAuth, [$this, $args]);
		}
		/**
		 * Выполнить алгоритм авторизации клиента
		 * @param  mixed Дополнительный массив аргументов
		 * @return mixed
		 */
		public function login($args = []){
			return call_user_func_array($this->cbLogin, [$this, $args]);
		}
		/**
		 * Выполняет ДЕавторизацию клиента
		 * @param  mixed Дополнительный массив аргументов
		 * @return mixed
		 */
		public function logout($args = []){
			return call_user_func_array($this->cbLogout, [$this, $args]);
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
		 * Возвращает пользователя по email
		 * @param  strin email
		 * @return array
		 */
		public function getUserByEmail($email){
			$user = $this->query("
select *
from users
where
	email = ?
limit 1;
			", [
				['s', $email]
			])

			return $user[0];
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

	}
