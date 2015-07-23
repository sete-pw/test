<?
	namespace Application\Test\View\Page;

	class Admin extends \View{

		function content($data){
			if($data['accept']){

				\CO::RE()->PUSH('js', '/assets/js/shop.common.js');
				\CO::RE()->PUSH('js', '/assets/js/admin.common.js');
?>
<!-- Модальные окна -->
<div class="modal fade" id="admin_modal_order_edit" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
				<h2 class="modal-title">
					Изменить место
				</h2>
			</div>
			<div class="modal-body">
				<div class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label for="inputEmail" class="col-lg-2 control-label">Столик</label>
							<div class="col-lg-10">
								
								<div class="btn-group">
									<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										<span id="table_title">Столик</span>
										<span class="caret"></span>
									</a>
									<ul id="table" class="dropdown-menu">
										
									</ul>
								</div>

							</div>
						</div>
						<div class="form-group">
							<label for="inputPassword" class="col-lg-2 control-label">Место</label>
							<div class="col-lg-10">
								
								<div class="btn-group">
									<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										<span id="set_title">Место</span>
										<span class="caret"></span>
									</a>
									<ul id="set" class="dropdown-menu">
										
									</ul>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-10 col-lg-offset-2">
								<button id="admin_button_edit" class="btn btn-primary">Изменить</button>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="admin_modal_order_delete" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
				<h2 class="modal-title">
					Удаление
				</h2>
			</div>
			<div class="modal-body">
				<div class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<h3 class="col-lg-offset-1">
								Пометить заказ, как выполненный?
							</h3>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-1">
								<button id="admin_button_edit_confirm" class="btn btn-primary">Пометить</button>
								<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Отмена</button>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- ПАНЕЛЬ УПРАВЛЕНИЯ -->
<div class="row">
	<h1>
		Очередь заказов
		<span id="admin_title" class="text-primary"></span>
	</h1>
</div>

<div class="row">
	<a id="admin_new_include" class="btn btn-success">Включить в очередь: <span id="admin_new_count">...</span> шт.</a>
</div>

<div class="row">
	<div class="container-scroll">
		<table class="table table-striped table-hover" id="admin_table">
			<thead>
				<tr>
					<th>Номер Заказа</th>
					<th>Клиент</th>
					<th>Место</th>
					<th>Стоимость</th>
					<th>Операции</th>
				</tr>
			</thead>
			<tbody id="admin_container" class="sort">



			</tbody>
		</table>
	</div>
</div>
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