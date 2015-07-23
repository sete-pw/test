var Api = {
	version: 1,
	url: '/api/v' + this.version + '/',
	method: 'GET',

	query: function(method, callback, data){
		$.ajax({
			url: this.url + method,
			method: this.method,
			dataType: 'json',

			data: data,

			success: callback
		});
	}
};

var Table = {
	getList: function(callback){
		Api.query('Table.getList', callback, {});
	},
	getSetList: function(tableId, callback){
		Api.query('Table.getSetList', callback, {
			table_id: tableId
		});
	}
};

var Bin = {
	get: function(callback){
		Api.query('Bin.get', callback, {});
	},
	getList: function(callback){
		Api.query('Bin.getList', callback, {});
	},
	add: function(setId, callback){
		Api.query('Bin.add', callback, {
			id_set: setId
		});
	},
	remove: function(orderSetId, callback){
		Api.query('Bin.remove', callback, {
			id_order_set: orderSetId
		});
	}
};

var Order = {
	getList: function(callback){
		Api.query('Order.getList', callback, {});
	},
	complete: function(orderSetId, callback){
		Api.query('Order.complete', callback, {
			id_order_set: orderSetId
		});
	}
};

var OrderSet = {
	swap: function(sortId_A, sortId_B, callback){
		Api.query('OrderSet.swap', callback, {
			sort_id_a: sortId_A,
			sort_id_b: sortId_B
		});
	}
};