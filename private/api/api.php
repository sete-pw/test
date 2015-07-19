<?
	require_once(DIR_ROOT . 'api/apiCore.php');
	print_r(CO::RE()->api);
	$ApiCore = new ApiCore(CO::RE()->api,CO::RE()->get);
	$ApiCore->callMethod();