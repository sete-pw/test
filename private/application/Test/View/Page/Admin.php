<?
	namespace Application\Test\View\Page;

	class Admin extends \View{

		function content($data){
			if($data['accept']){

				\CO::RE()->PUSH('js', '/assets/js/admin.common.js');
?>
<!-- ПАНЕЛЬ УПРАВЛЕНИЯ -->
<h1>ПАНЕЛЬ УПРАВЛЕНИЯ</h1>
<table class="table" id="active_orders_list">
	<thead>
		<tr>
			<th>Номер Заказа</th>
			<th>Клиент</th>
			<th>Номер столика</th>
			<th>Номер места</th>
			<th>Положение</th>
			<th>Стоимость</th>
			<th>Операции</th>
		</tr>
	</thead>
	<tbody id="order_container" class="sort">

		<tr id="item_1" data-id="1" data-sortid="3">
			<td>1</td>
			<td>ИмяКлиента</td>
			<td>13</td>
			<td>2</td>
			<td>3 ряд, 4 место</td>
			<td>2500 руб.</td>
			<td>
				<a href="#del_row" class="text-danger btn-delete" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a>
				&nbsp; 
				<a href="#set_rowModal" data-toggle="modal" class="text-primary" title="Редактировать"><span class="glyphicon glyphicon-file"></span></a> 
			</td>
		</tr>
		
	</tbody>
	<tbody>
		<tr>
			<td>ИТОГО</td>
			<td>1</td>
			<td>2</td>
			<td>3</td>
			<td></td>
			<td>7166 руб.</td>
			<td></td>
		</tr>
	</tbody>
</table>
<!-- END ПАНЕЛЬ УПРАВЛЕНИЯ -->
<?
			}else{
?>
<h1>
	У Вас нет доступа!
</h1>
<?
			}
		}
	}