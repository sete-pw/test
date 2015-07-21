<?
	namespace Application\Test\View;

	class Json extends \View{
		function content($data){

			echo json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
			
		}
	}