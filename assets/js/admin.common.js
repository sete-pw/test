chtml.turn = {
	list: {},
	title: {},

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

		Order.getList(function(data){
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
	},

	onDelete: function(id){
		chtml.turn.title.html('(удаляем)');

		Order.complete(id, function(data){
			if(data.status != 'success'){
				chtml.turn.title.html('(ошибка удаления)');
				chtml.turn.update();
			}else{
				chtml.turn.title.html('');
			}
		});
	},

	editRow: function(){
		console.log(chtml.turn.editId);
		$('#admin_modal_order_edit').modal('hide');
	},
	addRow: function(data){
		row = $('<tr></tr>').attr('id', 'admin_item_' + data.id_order_set).attr('data-id', data.id_order_set).attr('data-sortid', data.sort_id).append(
			$('<td></td>').html(data.id_order_set)
		).append(
			$('<td></td>').html(data.name)
		).append(
			$('<td></td>').html(data.position.replace(';', ', ')).append(
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

		chtml.turn.buttonEdit = $('#admin_button_edit');

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
			tr = $(this).closest('tr');
			chtml.turn.onDelete(tr.attr('data-id'));
			tr.remove();
		});

		chtml.turn.buttonEdit.click(chtml.turn.editRow);

		chtml.turn.update();
	}
};


$(document).ready(function() {

});

