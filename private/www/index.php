<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<h1>
<?
	CO::RE()->name = 'Test project';

	CO::RE()->hello = function(){
		echo 'Hello, this is ' . CO::RE()->name . '!';
	};

	CO::RE()->hello();
?>
</h1>



<?
	if(isset(CO::RE()->get['login'])){
?>
Пользователь авторизован
<?
	}else{
?>
Пользователь <strong>не авторизован</strong>
<?
	}
?>
</body>
</html>