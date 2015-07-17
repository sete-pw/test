<?
	header('Content-Type: text/html charset=windows-1251');

	require_once(DIR_ROOT . 'co.php');
	CO::RE();
	require_once(DIR_ROOT . 'init.php');
	CO::RE()->end();
	