<?
	namespace Application\Test\Model;

	class Orders extends \ModelSql{
		
		function data(){
			return $this->VALUES();
		}
	}