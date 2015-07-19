<?
	namespace Security;

	/**
	 * ����������� ������������
	 * @author ������ �������
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
		 * ����������� ������. ������ ������� ���������� � �������
		 * @param \SQL\DATA ����������� � ���� ������
		 * @param string ���� ��� ������
		 * @param function ������� ����������� �� �������
		 * @param function ������� ������������� �� �������
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
		 * ��������� �����������, ��������� ��������� �����������. ������������ � ��������
		 * @param  number id ������������
		 * @param  string ���-������ ������������
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
		 * ���������� ������ ������������ � ����
		 * @return array
		 */
		public function who(){
			return $this->user;
		}

		/**
		 * ��������� �������� ����������� ������� �� �������
		 * @param  mixed �������������� ������ ����������
		 * @return mixed
		 */
		public function auth($args = []){
			return call_user_func_array($this->cbAuth, [$this, $args]);
		}
		/**
		 * ��������� �������� ����������� �������
		 * @param  mixed �������������� ������ ����������
		 * @return mixed
		 */
		public function login($args = []){
			return call_user_func_array($this->cbLogin, [$this, $args]);
		}
		/**
		 * ��������� ������������� �������
		 * @param  mixed �������������� ������ ����������
		 * @return mixed
		 */
		public function logout($args = []){
			return call_user_func_array($this->cbLogout, [$this, $args]);
		}

		/**
		 * ���������� ���-������
		 * @param  number id ������������
		 * @param  string ������
		 * @return string
		 */
		public function getUserHash($id, $passwd){
			return md5($this->salt . '<id:' . $id . 'passwd:' . $passwd . '>end');
		}
		/**
		 * ���������� ������������ �� email
		 * @param  strin email
		 * @return array
		 */
		public function getUserByEmail($email){
			$user = $this->sql->query("
select *
from users
where
	email = ?
limit 1;
			", [
				['s', $email]
			]);

			return $user[0];
		}

		/**
		 * �������� �� ������������ �����������
		 * @return boolean
		 */
		public function unknown(){
			return ($this->level <= 0);
		}
		/**
		 * �������� �� ������������ ������������������
		 * @return boolean
		 */
		public function user(){
			return ($this->level >= 1);
		}
		/**
		 * �������� �� ������������ ���������������
		 * @return boolean
		 */
		public function admin(){
			return ($this->level >= 2);
		}

	}
