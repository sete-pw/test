chtml.turn = {
	list: {},
	title: {},

	buttonEdit: {},
	editId: -1,

	data: {},
	sort: [],
	sortBefore: [],

	results: {},

	clear: function(){
		chtml.turn.list.html('');
		chtml.turn.title.html('');
		chtml.turn.sortBefore = [];
	},

	update: function(){
		chtml.turn.title.html('(обновляем)');

		Order.getList(function(data){
			chtml.turn.clear();
			chtml.turn.data = data;

			$.each(data.response, function(key, value){
				chtml.turn.addRow(value);
			})
		});
	},

	onDelete: function(){
												//TODO
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
		chtml.turn.sortBefore.push(row.attr('data-id'));
	},

	onSort: function(e, ui){
		chtml.turn.sort = [];

		$.each(chtml.turn.list.children('tr'), function(key, value){
			row = $(value);
			chtml.turn.sort[key] = row.attr('data-id');
		});

		ida = 0;
		idb = 0;

		for(i = 0; i < chtml.turn.sort.length; i++){
			if(chtml.turn.sortBefore[i] != chtml.turn.sort[i]){
				ida = chtml.turn.sort[i];
				if(i>0){
					ida = chtml.turn.sort[i-1];
				}
				break;
			}
		}

		chtml.turn.title.html('(сохраняем)'); 
		OrderSet.swap(ida, idb, function(data){
			if(data.status != 'success'){
				chtml.turn.title.html('(ошибка сохранения!)');
				chtml.turn.update();
			}else{
				chtml.turn.title.html('');
			}
		});

		chtml.turn.sortBefore = chtml.turn.sort;
	},

	init: function(){
		chtml.turn.list = $('#admin_container');
		chtml.turn.title = $('#admin_title');

		chtml.turn.buttonEdit = $('#admin_button_edit');

		$("#admin_table tbody.sort").sortable({
			update: chtml.turn.onSort,
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
		})

		chtml.turn.buttonEdit.click(chtml.turn.editRow);

		chtml.turn.update();
	}
};


$(document).ready(function() {

});

