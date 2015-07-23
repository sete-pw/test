<?
	namespace Application\Test\View;

	class HtmlTemplate extends \View{
		private $data;

		private function active($name){
			if($this->data['active'] == $name){
				echo 'active';
			}
		}

		function content($data){
			$this->data = $data;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Столики онлайн</title>

	<!-- Bootstrap -->
	<link href="/assets/libs/bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">

	<link href="/assets/css/main.css" rel="stylesheet">

	<? foreach(\CO::RE()->css as $css){ ?>
	<link href="<?=$css?>" rel="stylesheet">
	<? } ?>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">Столики онлайн</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="<? $this->active('index'); ?>"><a href="/"><span class="glyphicon glyphicon-home"></span>&nbsp;Главная</a></li>
					<li class="<? $this->active('shop'); ?>"><a href="/shop"><span class="glyphicon glyphicon-barcode"></span>&nbsp;Заказать онлайн</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<!-- ОДНО ИЗ ДВУХ ДОЛЖНО БЫТЬ -->
					<? 
						if(\CO::AUTH()->user()){
					?>
					<li><a href="#binModal" data-toggle="modal"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Корзина&nbsp;<span id="bin_counter" class="badge">...</span></a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user">&nbsp;</span><?=\CO::AUTH()->who()->name?><span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li class="<? $this->active('user'); ?>"><a href="/user"><span class="glyphicon glyphicon-user"></span>&nbsp;Мой аккаунт</a></li>
					<?	
							if(\CO::AUTH()->admin()){//если админ то добавить пункт меню
					?>				
					<li class="<? $this->active('admin'); ?>"><a href="/admin"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;Очередь</a></li>
					<?
							}
					?>
							<li class="divider"></li>
							<li><a href="/logout"><span class="glyphicon glyphicon-remove"></span>&nbsp;Выйти</a></li>
						</ul>
					</li>
					<?
						} else {
					?>
					<li><a href="#loginModal" data-toggle="modal" class="text-success"> <span class="glyphicon glyphicon-lock text-success"></span>&nbsp;Войти</a></li>
					<? 
						} 
					?>
				</ul>
			</div>
		</div>
	</nav>
		
	<div class="modal fade" id="loginModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
					<h4 class="modal-title">Вход</h4>
				</div>
				<div class="modal-body">		
					<form class="form-horizontal" method="POST" action="/login">
						<fieldset>
							<div class="form-group">
								<label for="inputEmail" class="col-lg-2 control-label">Email</label>
								<div class="col-lg-10">
									<input class="form-control" id="inputEmail" placeholder="Email" type="text" name="email">
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="col-lg-2 control-label">Пароль</label>
								<div class="col-lg-10">
									<input class="form-control" id="inputPassword" placeholder="Пароль" type="password" name="passwd">
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-2">
									<button type="submit" class="btn btn-primary">Войти</button>
									<a class="btn btn-info" href="#reg">Регистрация</a>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="binModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
					<h2 class="modal-title">
						Корзина <span id="bin_title" class="text-primary"></span>
					</h2>
				</div>
				<div class="modal-body">		
					<div class="container-scroll">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Номер Заказа</th>
									<th>Место</th>
									<th>Стоимость</th>
									<th>Операции</th>
								</tr>
							</thead>
							<tbody id="bin_list">
								


							</tbody>
							<tbody>
								<tr>
									<td>ИТОГО</td>
									<td></td>
									<td>17500 руб.</td>
									<td></td>
								</tr>
							</tbody>
						</table> 
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="container" id="content">
		<?=$data['content']?>
	</div>





	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<!-- jQuery UI CSS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="/assets/libs/bootstrap-3.3.5/js/bootstrap.min.js"></script>

	<script src="/assets/js/api.js"></script>
	<script src="/assets/js/common.js"></script>

	<? if(\CO::AUTH()->user()){ ?>
		<script src="/assets/js/bin.js"></script>
	<? } ?>
	
	<? foreach(\CO::RE()->js as $js){ ?>
		<!--SORT TABLE in panel admininstrator-->
		<script src="<?=$js?>"></script>
	<? } ?>

</body>
</html>

<?
		}
	}