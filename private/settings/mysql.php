<?

	CO::SQL(new \SQL\DATA());
	
	CO::SQL()->connect('188.120.227.83', 'root', 'kolkol123', 'test_sete_pw');
	
	CO::SQL()->query("
		set names utf8;
	");

