chtml.turn = {
	list: {},
	title: {},

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

	},

	addRow: function(data){
		row = $('<tr></tr>').attr('id', 'admin_item_' + data.id_order_set).attr('data-id', data.id_order_set).attr('data-sortid', data.sort_id).append(
			$('<td></td>').html(data.id_order_set)
		).append(
			$('<td></td>').html(data.user_id)
		).append(
			$('<td></td>').html(data.table_id)
		).append(
			$('<td></td>').html(data.set_id)
		).append(
			$('<td></td>').html(data.position)
		).append(
			$('<td></td>').html(data.price)
		).append(
			$('<td></td>').append(
				$('<a></a>').addClass('admin-row-delete').addClass('text-primary').attr('title', 'Изменить').attr('data-toggle', 'modal').attr('href', '#admin_modal_order_edit').append(
					$('<span></span>').addClass('glyphicon').addClass('glyphicon-file')
				)
			).append(
				$('<span></span>').html('&nbsp;')
			).append(
				$('<a></a>').addClass('admin-row-edit').addClass('text-danger').attr('title', 'Удалить').append(
					$('<span></span>').addClass('glyphicon').addClass('glyphicon-remove')
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

		$("#admin_table tbody.sort").sortable({
			update: chtml.turn.onSort
		}).disableSelection();

		chtml.turn.update();
	}
};


$(document).ready(function() {

});

