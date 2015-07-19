<?
	namespace SQL;

	/**
	 * Реализует взаимодействие с MySQL
	 * @author Сергей Терехов
	 */
	class DATA{
		/**
		 * Экземпляр соединения с базой данных
		 * @var mysqli
		 */
		private $connect;
		/**
		 * Соединение с базой установленно?
		 * @var boolean
		 */
		private $connected = false;
		/**
		 * Возвращяет строку в виде ассоциативного массива
		 * @param  stmt Запрос
		 * @return array
		 */
		private function stmtRowAssoc (&$stmt){
			if($stmt instanceof \mysqli_stmt){
				$data = mysqli_stmt_result_metadata($stmt);
				if(false !== $data){
					$args = [$stmt];
					$field = [];
					
					while($f = $data->fetch_field()){
						$f = $f->name;
						$field[$f] = $f;
						$args[] = &$field[$f];
					}
					
					call_user_func_array(mysqli_stmt_bind_result, $args);
					
					if($stmt->fetch()){
						return $field;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		/**
		 * Выполняет запрос в базу и возвращает результат в виде ассоциативного массива
		 * @param  string Запрос для prepare
		 * @param  array Переменные запроса [type => value]
		 * @param  string Поле для индекса массива
		 * @return array
		 */
		public function query($query, $vars = null, $fieldArrayIndex = false){
			$stmt = $this->connect->prepare($query);
			
			if(false === $stmt){
				die('prepare() failed: ' . htmlspecialchars($this->connect->error));
			}
			
			if(false !== $stmt){
				if(false === is_null($vars)){
					$types = array();
					
					foreach($vars as $k => $v){
						$types[] = $v[0];
					}
					
					$types = implode($types);
					
					$args = array(
						$stmt,
						$types
					);
					
					foreach($vars as $k => $v){
						$args[] = &$vars[$k][1];
					}
					
					call_user_func_array(mysqli_stmt_bind_param, $args);
				}
				
				$stmt->execute();
				
				$result = array();
				while($row = $this->stmtRowAssoc($stmt)){
					if(false === $fieldArrayIndex){
						$result[] = $row;
					}else{
						$result[$row[$fieldArrayIndex]] = $row;
					}
				}
				
				return $result;
			}else{
				return false;
			}
		}
		/**
		 * Возвращает идентификатор вставленной записи
		 * @return number
		 */
		public function iid(){
			return $this->connect->insert_id;
		}
		/**
		 * Возвращает информацию об ошибке
		 * @return string
		 */
		public function error(){
			return $this->connect->error;
		}
		/**
		 * Выполняет подключение к базе данных
		 * @param  string Адрес сервера
		 * @param  string Имя пользователя
		 * @param  string Пароль пользователя
		 * @param  string База данных по умолчанию = information_schema
		 */
		public function connect($host = 'localhost', $user = '', $passwd = '', $dbName = 'information_schema'){
			$this->connect = mysqli_connect($host, $user, $passwd, $dbName);
			if($this->connect){
				$this->connected = true;
			}
		}
		/**
		 * Проверяет установленно ли соединение с базой
		 * @return boolean
		 */
		public function isConnect(){
			return $this->connected;
		}
		/**
		 * Изменяет выбранную базу данных
		 * @param  string Имя базы данных
		 */
		public function dbSelect($dbName){
			$this->connect->select_db($dbName);
		}
		/**
		 * Разрывает соединение с базой данных
		 */
		public function disconnect(){
			$this->connect->close();
		}
		
	}
	