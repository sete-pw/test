<?
	namespace SQL;

	/**
	 * ��������� �������������� � MySQL
	 * @author ������ �������
	 */
	class DATA{
		/**
		 * ��������� ���������� � ����� ������
		 * @var mysqli
		 */
		private $connect;
		/**
		 * ���������� � ����� ������������?
		 * @var boolean
		 */
		private $connected = false;
		/**
		 * ���������� ������ � ���� �������������� �������
		 * @param  stmt ������
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
		 * ��������� ������ � ���� � ���������� ��������� � ���� �������������� �������
		 * @param  string ������ ��� prepare
		 * @param  array ���������� ������� [type => value]
		 * @param  string ���� ��� ������� �������
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
		 * ���������� ������������� ����������� ������
		 * @return number
		 */
		public function iid(){
			return $this->connect->insert_id;
		}
		/**
		 * ���������� ���������� �� ������
		 * @return string
		 */
		public function error(){
			return $this->connect->error;
		}
		/**
		 * ��������� ����������� � ���� ������
		 * @param  string ����� �������
		 * @param  string ��� ������������
		 * @param  string ������ ������������
		 * @param  string ���� ������ �� ��������� = information_schema
		 */
		public function connect($host = 'localhost', $user = '', $passwd = '', $dbName = 'information_schema'){
			$this->connect = mysqli_connect($host, $user, $passwd, $dbName);
			if($this->connect){
				$this->connected = true;
			}
		}
		/**
		 * ��������� ������������ �� ���������� � �����
		 * @return boolean
		 */
		public function isConnect(){
			return $this->connected;
		}
		/**
		 * �������� ��������� ���� ������
		 * @param  string ��� ���� ������
		 */
		public function dbSelect($dbName){
			$this->connect->select_db($dbName);
		}
		/**
		 * ��������� ���������� � ����� ������
		 */
		public function disconnect(){
			$this->connect->close();
		}
		
	}
	