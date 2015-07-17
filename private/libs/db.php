<?
	namespace SQL;

	class DATA{
		private $connect;
		
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
		
		public function iid(){
			return $this->connect->insert_id;
		}

		public function error(){
			return $this->connect->error;
		}
		
		public function connect($host = 'localhost', $user = '', $passwd = '', $dbName = 'test'){
			$this->connect = mysqli_connect($host, $user, $passwd, $dbName);
		}
		
		public function dbSelect($dbName){
			$this->connect->select_db($dbName);
		}
		
		public function disconnect(){
			$this->connect->close();
		}
		
	}
	