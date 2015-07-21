<?
	namespace Application\Test\Model;

	class Order extends \ModelSql{
		protected $table = 'orders';
		
		function data(){
			return $this->VALUES();
		}
	}