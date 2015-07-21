<?
	namespace Application\Test\Model;

	class Set extends \ModelSql{
		protected $table = 'sets';
		
		function data(){
			return $this->VALUES();
		}

		function getList(){
			$return = $this;
		}
	}