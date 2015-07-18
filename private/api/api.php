<?
	define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/private/');
	require_once(DIR_ROOT . 'host.php');
	//require_once(DIR_ROOT . 'api/apiCore.php');
	echo CO::RE()->url;
