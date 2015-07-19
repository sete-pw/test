<?
	require_once(DIR_ROOT . 'api/apiCore.php');
	$url = explode('/',CO::RE()->url);
	$function = explode('=',$url[1]);

	$apiFunctionName = $function[0];
	$apiFunctionParams = $function[1];

	$ApiCore = new ApiCore($apiFunctionName,$apiFunctionParams);