chtml.bin = {
	list: {},
	title: {},
	buttonAdd: {},

	add: function(){
		chtml.bin.buttonAdd.addClass('disabled');

		Bin.add(chtml.set.selected.id_set, function(data){
			if(data.status == 'success'){
				chtml.bin.addRow({
					id_order_set: data.response.id_order_set,
					position: chtml.table.selected.position + ', ' + chtml.set.selected.position,
					price: chtml.table.selected.price
				});
			}else{
				alert('Возникла ошибка добавления заказа!');
			}

			chtml.bin.buttonAdd.removeClass('disabled');
			chtml.table.update();
		});
	},
	remove: function(tr){
		id = tr.attr('data-id');
		chtml.bin.removeRow(id);

		chtml.table.update();

		Bin.remove(id, function(data){
			if(data.status != 'success'){
				chtml.table.update();
				alert('Ошибка удаления заказа!');
			}
		});
	},

	addRow: function(data){
		row = $('<tr></tr>').attr('data-id', data.id_order_set);

		$('<td></td>').appendTo(row).html(data.id_order_set);
		$('<td></td>').appendTo(row).html(data.position);
		$('<td></td>').appendTo(row).html(data.price);
		$('<td></td>').appendTo(row).append(
			$('<a></a>').attr('title', 'Удалить').addClass('text-danger').addClass('bin-row-delete').append(
				$('<span></span>').addClass('glyphicon').addClass('glyphicon-remove')
			)
		);
		
		chtml.bin.list.append(row);
		chtml.bin.counter.html(++chtml.bin.count);
	},
	removeRow: function(id){
		chtml.bin.list.children('tr[data-id=' + id + ']').remove();
		chtml.bin.counter.html(--chtml.bin.count);
	},

	clear: function(){
		chtml.bin.list.html('');
		chtml.bin.title.html('');
		chtml.bin.counter.html(chtml.bin.count = 0);
	},

	update: function(){
		chtml.bin.title.html('(обновляем)');

		Bin.getList(function(data){
			chtml.bin.clear();

			$.each(data.response, function(key, value){
				chtml.bin.addRow({
					id_order_set: value.id_order_set,
					position: value.position.replace(';', ', '),
					price: value.price
				});
			});
		});
	},

	init: function(){
		chtml.bin.list = $('#bin_list');
		chtml.bin.title = $('#bin_title');
		chtml.bin.counter = $('#bin_counter');
		chtml.bin.buttonAdd = $('#order_add');

		chtml.bin.buttonAdd.click(function(e){
			chtml.bin.add();
		});
		chtml.bin.list.on('click', '.bin-row-delete', function(){
			chtml.bin.remove( $(this).closest('tr') );
		});

		chtml.bin.update();
	}
};