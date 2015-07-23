chtml.turn = {
	list: {},
	title: {},

	includeButton: {},
	includeButtonCount: {},

	buttonEdit: {},
	editId: -1,

	data: {},

	results: {},

	clear: function(){
		chtml.turn.list.html('');
		chtml.turn.title.html('');
	},

	update: function(){
		chtml.turn.title.html('(обновляем)');

		OrderSet.getList(function(data){
			chtml.turn.clear();
			chtml.turn.data = data;

			if(data.status == 'success'){
				$.each(data.response, function(key, value){
					chtml.turn.addRow(value);
				});
			}else{
				chtml.turn.title.html('(ошибка)');
			}
		});

		chtml.turn.includeButton.hide();

		OrderSet.getQeue(function(data){
			if(data.status == 'success'){
				if(data.response.count > 0){
					chtml.turn.includeButtonCount.html(data.response.count);
					chtml.turn.includeButton.show();
				}
			}else{
				chtml.turn.title.html('(ошибка)');
			}
		});
	},

	onDelete: function(id){
		chtml.turn.title.html('(удаляем)');

		OrderSet.complete(id, function(data){
			if(data.status == 'success'){
				chtml.turn.title.html('');
			}else{
				chtml.turn.title.html('(ошибка удаления)');
				chtml.turn.update();
			}
		});
	},

	include: function(){
		chtml.turn.includeButton.hide();
		chtml.turn.title.html('(выполняется)');

		OrderSet.includeQeue(function(data){
			if(data.status == 'success'){
				chtml.turn.update();
			}else{
				chtml.turn.title.html('(ошибка)');
			}
		});
	},
	deleteRow: function(){
		chtml.turn.title.html('(удаляем)');

		OrderSet.complete(chtml.turn.editId, function(data){
			if(data.status == 'success'){
				tr = $(this).closest('tr');
				chtml.turn.onDelete(tr.attr('data-id'));
				tr.remove();
				chtml.turn.title.html('');
			}else{
				chtml.turn.title.html('ошибка удаления');
			}
		});
	},
	editRow: function(){
		$('#admin_modal_order_edit').modal('hide');
		chtml.turn.title.html('(изменяем)');

		OrderSet.edit(chtml.turn.editId, chtml.set.select, function(data){
			if(data.status == 'success'){
				chtml.turn.title.html('');
				chtml.turn.list.children(
					'.admin-item[data-id='+ chtml.turn.editId +']'
				).children(
					'td.admin-item-position'
				).html(
					chtml.table.selected.position
					+ ', ' +
					chtml.set.selected.position
				);
			}else{
				chtml.turn.title.html('(ошибка изменения)');
				chtml.turn.update();
			}
		});
	},
	addRow: function(data){
		row = $('<tr></tr>').attr('id', 'admin_item_' + data.id_order_set).addClass('admin-item').attr('data-id', data.id_order_set).attr('data-sortid', data.sort_id).append(
			$('<td></td>').html(data.id_order_set)
		).append(
			$('<td></td>').html(data.name)
		).append(
			$('<td class="admin-item-position"></td>').html(data.position.replace(';', ', ')).append(
				$('<span></span>').html('&nbsp;')
			).append(
				$('<a></a>').addClass('admin-row-edit').addClass('text-primary').attr('title', 'Изменить').attr('data-toggle', 'modal').append(
					$('<span></span>').addClass('glyphicon').addClass('glyphicon-pencil')
				)
			)
		).append(
			$('<td></td>').html(data.price)
		).append(
			$('<td></td>').append(
				$('<a></a>').addClass('admin-row-delete').addClass('text-success').attr('title', 'Удалить').append(
					$('<span></span>').addClass('glyphicon').addClass('glyphicon-ok')
				)
			)
		);

		row.appendTo(chtml.turn.list);
	},

	onSort: function(e, ui){
		chtml.turn.title.html('(сохраняем)');
		OrderSet.swap(chtml.turn.startPos +1, ui.item.index() +1, function(data){
			if(data.status != 'success'){
				chtml.turn.title.html('(ошибка сохранения!)');
				chtml.turn.update();
			}else{
				chtml.turn.title.html('');
			}
		});
	},

	init: function(){
		chtml.turn.list = $('#admin_container');
		chtml.turn.title = $('#admin_title');
		chtml.turn.titleError = $('#admin_title_error');

		chtml.turn.buttonEdit = $('#admin_button_edit');
		chtml.turn.buttonEditConfirm = $('#admin_button_edit_confirm');

		chtml.turn.includeButton = $('#admin_new_include');
		chtml.turn.includeButtonCount = $('#admin_new_count');

		$("#admin_table tbody.sort").sortable({
			update: chtml.turn.onSort,
			start: function(event, ui) {
				chtml.turn.startPos = ui.item.index();
			},
			helper: function(e, ui){
				ui.children().each(function() {
					$(this).width($(this).width());
				});
				return ui;
			}
		}).disableSelection();

		chtml.turn.list.on('click', '.admin-row-edit', function(){
			chtml.turn.editId = $(this).closest('tr').attr('data-id');
			$('#admin_modal_order_edit').modal('show');
		});
		chtml.turn.list.on('click', '.admin-row-delete', function(){
			chtml.turn.editId = $(this).closest('tr').attr('data-id');

		});

		chtml.turn.buttonEdit.click(chtml.turn.editRow);
		chtml.turn.buttonEditConfirm.click(chtml.turn.deleteRow);

		chtml.turn.includeButton.click(chtml.turn.include);

		chtml.turn.update();
	}
};


$(document).ready(function() {
	chtml.set.buttonSelect = $('#admin_button_edit');
});

