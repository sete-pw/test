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

			success: callback,
			error: callback
		});
	}
};

var Table = {
	getList: function(callback){
		Api.query('Table.getList', callback, {});
	}
};

var Set = {
	getList: function(tableId, callback){
		Api.query('Set.getList', callback, {
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
	},

	pay: function(orderId, callback){
		Api.query('Bin.pay', callback, {
			id_order: orderId
		});
	}
};

var OrderSet = {
	getList: function(callback){
		Api.query('OrderSet.getList', callback, {});
	},

	complete: function(orderSetId, callback){
		Api.query('OrderSet.complete', callback, {
			id_order_set: orderSetId
		});
	},
	edit: function(orderSetId, newSetId, callback){
		Api.query('OrderSet.edit', callback, {
			id_order_set: orderSetId,
			id_set: newSetId
		});
	},
	swap: function(sortId_before, sortId_after, callback){
		Api.query('OrderSet.swap', callback, {
			sort_id_before: sortId_before,
			sort_id_after: sortId_after
		});
	},

	getQeue: function(callback){
		Api.query('OrderSet.getQeue', callback, {});
	},
	includeQeue: function(callback){
		Api.query('OrderSet.includeQeue', callback, {});
	}
};