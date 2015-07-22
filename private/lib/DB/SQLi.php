<?
	namespace DB;

	class SQLi{
		private $lastStmt;
		private $lastVars;

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
		private function stmtRowAssoc ($stmt){
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
		public function query($query = null, $vars = null, $fieldArrayIndex = false){
			$noExecute = false;

			if(!is_null($query)){
				if(is_array($query)){
					$noExecute = true;
					$query = $query[0];
				}

				$stmt = $this->connect->prepare($query);
				$this->lastStmt = $stmt;
			
				if(false === $stmt){
					var_dump(['query' => $query, 'args_json' => json_encode($vars)]);
					die('prepare() failed: ' . $this->connect->error);
				}else{
					if(false === is_null($vars) && count($vars)){
						$types = array();
						
						foreach($vars as $k => $v){
							$types[] = $v[0];
						}
						
						$types = implode($types);
						
						$args = array(
							$stmt,
							$types
						);
						
						$this->lastVars = [];

						foreach($vars as $k => $v){
							$this->lastVars[$k] = $v[1];
							$args[] = &$this->lastVars[$k];
						}

						call_user_func_array(mysqli_stmt_bind_param, $args);
					}
				}
			}else{
				$stmt = $this->lastStmt;

				foreach ($vars as $k => $v) {
					$this->lastVars[$k] = $v;
				}
			}

			if(!$noExecute){
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
				return $this;
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