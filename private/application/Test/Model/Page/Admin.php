<?
	namespace Application\Test\Model\Page;

	class Admin extends \Model{
		function __construct(){
			$this->data['accept'] = \CO::AUTH()->admin();
		}
	}