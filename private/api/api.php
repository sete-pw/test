<?
	require_once(DIR_ROOT . 'api/apiCore.php');
	print_r(CO::RE()->get);
	$ApiCore = new ApiCore(CO::RE()->api,CO::RE()->post);