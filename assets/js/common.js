var chtml = {

	init: function(){
		$.each(chtml, function(key, value){
			if(key != 'init'){
				value.init();
			}
		});
	}
};

$(document).ready(function() {

	chtml.init();

});