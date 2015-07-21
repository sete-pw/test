<?
	namespace Application\Test\Model;

	class User extends \ModelSql{
		protected $table = 'users';

		function data(){
			return $this->VALUES();
		}
	}