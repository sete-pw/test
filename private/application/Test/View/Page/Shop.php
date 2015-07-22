<?
	namespace Application\Test\View\Page;

	class Shop extends \View{

		function content($data){
			if($data['accept']){

				\CO::RE()->PUSH('js', '/assets/js/shop.common.js');
?>
<form id="order_form">

	<div>
		<div class="btn-group">
			<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
				<span id="table_title">Столик</span>
				<span class="caret"></span>
			</a>
			<ul id="table" class="dropdown-menu">
				
			</ul>
		</div>

		<div class="btn-group">
			<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
				<span id="set_title">Место</span>
				<span class="caret"></span>
			</a>
			<ul id="set" class="dropdown-menu">
				
			</ul>
		</div>

		<a id="order_add" class="btn btn-primary">Добавить в корзину</a>
	</div>

</form>
<?
			}
		}
	}