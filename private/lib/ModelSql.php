<?
	abstract class ModelSql{
		private $sql;

		protected $table;

		private $type;
		private $place;
		private $value;
		private $update;

		final public function __construct(){
			$spaces = explode('\\', get_called_class());
			if(is_null($this->table)){
				$this->table = strtolower(array_pop($spaces));
			}
			$this->sql = CO::SQL();

			$this->update = [];

			$places = $this->sql->query("
select COLUMN_NAME, COLUMN_TYPE
from information_schema.columns 
where
	table_name = ?
	and
	table_schema = database();
			", [
				['s', $this->table]
			]);
			foreach($places as $column){
				$this->type[$column['COLUMN_NAME']] = $column['COLUMN_TYPE'];
				$this->place[] = $column['COLUMN_NAME'];
			}
		}

		public function QUERY($query, $params, $indexPlace){
			return $this->sql->query($query, $params, $indexPlace);
		}

		private function FINDBY($place, $value){
			$res = $this->sql->query("
select *
from `".$this->table."`
where
	`".str_replace('`', '', $place)."` = ?
limit 1;
			", [
				['s', $value]
			]);

			if(count($res)){
				$this->value = $res[0];
				return $this;
			}else{
				return false;
			}
		}

		public function __call($name, $args){
			$keyword = 'findBy_';
			if(substr($name, 0, strlen($keyword)) == $keyword){
				return $this->FINDBY(substr($name, strlen($keyword)), $args[0]);
			}
		}

		public function __isset($name){
			return isset($this->type[$name]);
		}
		public function __get($name){
			if(isset($this->type[$name])){
				return $this->value[$name];
			}
		}
		public function __set($name, $value){
			if(isset($this->type[$name])){
				$this->update[$name] = $value;
			}
		}

		public function ID(){
			return $this->value[$this->PID()];
		}
		public function PID(){
			return $this->place[0];
		}

		public function CREATE(){
			$place = [];
			$value = [];
			$valueString = [];
			foreach ($this->update as $key => $value) {
				$place[] = '`' . $key . '`';
				$valueString[] = '?';
				$value[] = ['s', $value];
			}
			$this->sql->query("
insert into `".$this->table."`
(
	".implode(',', $place)."
)values(
	".implode(',', $valueString)."
);
			", $value);

			if($iid = $this->sql->iid()){
				$this->FINDBY($this->PID(), $iid);
				return true;
			}else{
				return false;
			}
		}

		public function DELETE(){
			$this->sql->query("
delete from `".$this->table."`
where
	`".$this->PID()."` = ?
limit 1;
			", [
				['i', $this->ID()]
			]);

			$this->value = [];
			$this->update = [];
		}

		public function UPDATE(){
			if(count($this->update)){
				$query = '';
				$queryVal = [];

				foreach ($this->update as $key => $value) {
					if($value !== $this->value[$key]){
						$query .= ' `' . $key . '` = ? ';
						$queryVal[] = ['s', $value];
						$this->value[$key] = $value;
					}
				}
				if(count($queryVal)){
					$queryVal[] = ['i', $this->ID];

					$this->sql->query("
		update `".$this->table."`
		set
			".$query."
		where
			`".$this->PID."` = ?
		limit 1;
					", $queryVal);
				}
			}
		}

		public function VALUES(){
			return $this->value;
		}
	}