chtml.turn = {
	list: {},
	results: {},

	clear: function(){
		chtml.turn.list.html('');
	},

	update: function(){
		chtml.turn.clear();

	},

	init: function(){
		chtml.turn.list = $('#order_container');

		chtml.turn.update();
	}
};


$(document).ready(function() {

	//Make table sortable
	$("#active_orders_list tbody.sort").sortable({
    	update: function(e, ui){
    		var data = $(this).sortable('serialize');
    		console.log(data);
    	}
	}).disableSelection();
	


});

