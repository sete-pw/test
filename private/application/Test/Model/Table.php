<?
	namespace Application\Test\Model;

	class Table extends \ModelSql{
		protected $table = 'tables';
		
		function data(){
			return $this->VALUES();
		}
	}