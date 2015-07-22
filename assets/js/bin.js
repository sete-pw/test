chtml.bin = {
	list: {},
	buttonAdd: {},

	add: function(){
		chtml.bin.buttonAdd.addClass('disabled');

		Bin.add(chtml.set.selected.id_set, function(data){
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
	},

	init: function(){
		chtml.bin.list = $('#bin_list');
		chtml.bin.buttonAdd = $('#order_add');

		chtml.bin.buttonAdd.click(function(e){
			chtml.bin.add();
		});
		chtml.bin.list.on('click', '.bin-row-delete', function(){
			chtml.bin.remove( $(this).closest('tr').attr('data-item') );
		});
	}
};