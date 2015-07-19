<?
	require_once(DIR_ROOT . 'api/apiCore.php');
	$url = explode('/',CO::RE()->url);
	echo $url[1];
