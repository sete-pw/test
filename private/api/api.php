<?
	require_once(DIR_ROOT . 'api/apiCore.php');
	$ApiCore = new ApiCore(CO::RE()->api,CO::RE()->get['params']);
	print_r($ApiCore->callMethod());