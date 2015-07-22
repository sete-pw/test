

var chtml = {
	turn: {

	}

	init: function(){

	}
};


$(document).ready(function() {

	chtml.init();

	//Make table sortable
	$("#active_orders_list tbody.sort").sortable({
    	update: function(e, ui){
    		var data = $(this).sortable('serialize');
    		console.log(data);
    	}
	}).disableSelection();
	


});

