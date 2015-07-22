
chtml.table = {
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
	},

	init: function(){
		chtml.table.list = $('#table');
		chtml.table.title = $('#table_title');

		chtml.table.list.on('click', '.table-item', function(){
			chtml.table.setSelect( $(this).attr('data-item') );
		});
	}
};

chtml.set = {
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
	},

	init: function(){
		chtml.set.list = $('#set');
		chtml.set.title = $('#set_title');

		chtml.set.list.on('click', '.set-item', function(){
			chtml.set.setSelect( $(this).attr('data-item') );
		});
	}
};


$(document).ready(function(){
	chtml.table.update();
});