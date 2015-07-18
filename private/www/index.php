<?
	CO::RE()->name = 'Test project';

	CO::RE()->hello = function(){
		echo 'Hello, this is ' . CO::RE()->name . '!';
	};
	
	CO::RE()->hello();