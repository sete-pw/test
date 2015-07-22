<?
	namespace Application\Test\View\Page;

	class Admin extends \View{

		function content($data){
			if($data['accept']){

				\CO::RE()->PUSH('js', '/assets/js/admin.common.js');
?>
<!-- Модальные окна -->
<div class="modal fade" id="set_rowModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
				<h4 class="modal-title">Редактировать</h4>
			</div>
			<div class="modal-body">
				
			</div>
		</div>
	</div>
</div>


<!-- ПАНЕЛЬ УПРАВЛЕНИЯ -->
<h1>
	Управление заказами <span id="admin_title" class="text-primary"></span>
</h1>

<table class="table" id="admin_table">
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
	<tbody id="admin_container" class="sort">



	</tbody>
	<tbody>
		<tr>
			<td>ИТОГО</td>
			<td></td>
			<td></td>
			<td></td>
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