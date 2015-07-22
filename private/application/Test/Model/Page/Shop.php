<?
	namespace Application\Test\Model\Page;

	class Shop extends \Model{
		function __construct(){
			$this->data['accept'] = \CO::AUTH()->user();
		}
	}