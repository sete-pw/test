
var chtml = {
	table: {
		list: {},
		title: {},
		select: -1,
		selected: {},

		data: {},

		reset: function(){
			chtml.table.setSelect(-1);
		},
		setSelect: function(id){
			chtml.table.select = id;
			if(id >= 0){
				chtml.table.selected = chtml.table.data.response[chtml.table.select];
				chtml.table.title.html( chtml.table.selected.position );
				chtml.set.update();
			}else{
				chtml.table.selected = {};
				chtml.table.title.html('Столик');
				chtml.set.clear();
			}
		},
		clear: function(){
			chtml.set.clear();
			chtml.table.reset();
			chtml.table.list.html('');
		},
		fillList: function(){
			chtml.table.reset();
			$.each(chtml.table.data.response, function(key, value){
				e = $('<a></a>').addClass('table-item').attr('data-item', key).html(value.position);
				e = $('<li></li>').append(e);
				e.appendTo(chtml.table.list);
			});
		},
		update: function(){
			chtml.table.clear();
			chtml.table.title.html('Загрузка...');
			Table.getList(function(data){
				chtml.table.data = data;
				chtml.table.fillList();
			});
		}
	},
	set: {
		list: {},
		title: {},
		select: -1,
		selected: {},

		data: {},

		reset: function(){
			chtml.set.setSelect(-1);
		},
		setSelect: function(id){
			chtml.set.select = id;
			if(id >= 0){
				chtml.set.selected = chtml.set.data.response[chtml.set.select];
				chtml.set.title.html( chtml.set.selected.position );
				chtml.bin.buttonAdd.removeClass('disabled');
			}else{
				chtml.set.selected = {};
				chtml.set.title.html('Место');
				chtml.bin.buttonAdd.addClass('disabled');
			}
		},
		clear: function(){
			chtml.set.reset();
			chtml.set.list.html('');
		},
		fillList: function(){
			chtml.set.reset();
			$.each(chtml.set.data.response, function(key, value){
				e = $('<a></a>').addClass('set-item').attr('data-item', key).html(value.position);
				e = $('<li></li>').append(e);
				e.appendTo(chtml.set.list);
			});
		},
		update: function(){
			chtml.set.clear();
			chtml.set.title.html('Загрузка...');
			Table.getSetList(chtml.table.select, function(data){
				chtml.set.data = data;
				chtml.set.fillList();
			});
		}
	},

	bin: {
		list: {},
		buttonAdd: {},

		add: function(){
			chtml.bin.buttonAdd.addClass('disabled');

			Bin.add(chtml.set.selected.id_set, function(data){console.log(data);
				if(data.status == 'success'){
					table = chtml.table.selected;
					set = chtml.set.selected;

					row = $('<tr></tr>').attr('data-item', data.response.id_order_set);

					$('<td></td>').appendTo(row).html(data.response.id_order_set);
					$('<td></td>').appendTo(row).html(table.id_table);
					$('<td></td>').appendTo(row).html(set.id_set);
					$('<td></td>').appendTo(row).html(table.position + ', ' + set.position);
					$('<td></td>').appendTo(row).html(table.price);
					$('<td></td>').appendTo(row).append(
						$('<a></a>').attr('title', 'Удалить').addClass('text-danger').addClass('bin-row-delete').append(
							$('<span></span>').addClass('glyphicon').addClass('glyphicon-remove')
						)
					);
					
					chtml.bin.list.append(row);
				}else{
					alert('Возникла ошибка добавления заказа!');
				}

				chtml.bin.buttonAdd.removeClass('disabled');
				chtml.table.update();
			});
		},
		remove: function(id){
			Bin.remove(id, function(data){
				if(data.status == 'success'){
					chtml.bin.list.children('tr[data-item=' + id + ']').remove();
				}else{
					alert('Ошибка удаления заказа!');
				}

				chtml.table.update();
			});
		}
	},

	init: function(){
		chtml.table.list = $('#table');
		chtml.table.title = $('#table_title');

		chtml.set.list = $('#set');
		chtml.set.title = $('#set_title');

		chtml.bin.list = $('#bin_list');
		chtml.bin.buttonAdd = $('#order_add');

		chtml.table.list.on('click', '.table-item', function(){
			chtml.table.setSelect( $(this).attr('data-item') );
		});
		chtml.set.list.on('click', '.set-item', function(){
			chtml.set.setSelect( $(this).attr('data-item') );
		});
		chtml.bin.buttonAdd.click(function(e){
			chtml.bin.add();
		});
		chtml.bin.list.on('click', '.bin-row-delete', function(){
			chtml.bin.remove( $(this).closest('tr').attr('data-item') );
		});

		chtml.table.update();
	},
};

$(document).ready(function(){
	chtml.init();
});