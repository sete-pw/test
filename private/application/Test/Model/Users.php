<?
	namespace Application\Test\Model;

	class Users extends \ModelSql{

		function data(){
			return $this->VALUES();
		}
	}