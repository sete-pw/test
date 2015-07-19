<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="windows-1251">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Столики онлайн</title>

		<!-- Bootstrap -->
		<link href="../../assets/libs/bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<link href="../../assets/css/custom-style.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">Столики онлайн</a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active"><a href="/"><span class="glyphicon glyphicon-home"></span> Главная<span class="sr-only">(current)</span></a></li>
						<li><a href="#shop"><span class="glyphicon glyphicon-barcode"></span> Заказать Онлайн</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<!-- ОДНО ИЗ ДВУХ ДОЛЖНО БЫТЬ -->
						<? 
							if(CO::AUTH()->user()){
						?>
						<li><a href="#binModal" data-toggle="modal"><span class="glyphicon glyphicon-shopping-cart"></span> Корзина <span class="badge">0</span></a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> <?=CO::AUTH()->who()['name'];?><span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="#settings"><span class="glyphicon glyphicon-cog"></span> Настройки</a></li>
						<?	
								if(CO::AUTH()->admin()){//если админ то добавить пункт меню
						?>				
						<li><a href="#admin"><span class="glyphicon glyphicon-wrench"></span> Управление</a></li>
						<?
								}
						?>
								<li class="divider"></li>
								<li><a href="/logout"><span class="glyphicon glyphicon-remove"></span> Выйти</a></li>
							</ul>
						</li>
						<?
							} else {
						?>
						<li><a href="#loginModal" data-toggle="modal" class="text-success"> <span class="glyphicon glyphicon-lock text-success"></span> Войти </a></li>
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
										<button type="submit" class="btn btn-primary"> Войти </button>
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
						<h4 class="modal-title">Корзина</h4>
					</div>
					<div class="modal-body">		
						<?/*Номер заказа; Номер столика; номер места; положение (типа Третий ряд, четвертый стул); стоимость; кнопка Удалить*/?>
						<table class="table table-striped table-hover ">
							<thead>
								<tr>
									<th>Номер Заказа</th>
									<th>Номер столика</th>
									<th>Номер места</th>
									<th>Положение</th>
									<th>Стоимость</th>
									<th>Операции</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1488</td>
									<td>13</td>
									<td>2</td>
									<td>3 ряд,<br> 4 место</td>
									<td>2500 руб.</td>
									<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a></td>
								</tr>
								<tr>
									<td>1488</td>
									<td>13</td>
									<td>2</td>
									<td>3 ряд,<br> 4 место</td>
									<td>2500 руб.</td>
									<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a></td>
								</tr>
								<tr>
									<td>1488</td>
									<td>13</td>
									<td>2</td>
									<td>3 ряд,<br> 4 место</td>
									<td>2500 руб.</td>
									<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a></td>
								</tr>
								<tr>
									<td>1488</td>
									<td>13</td>
									<td>2</td>
									<td>3 ряд,<br> 4 место</td>
									<td>2500 руб.</td>
									<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a></td>
								</tr>
								<tr>
									<td>1488</td>
									<td>13</td>
									<td>2</td>
									<td>3 ряд,<br> 4 место</td>
									<td>2500 руб.</td>
									<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a></td>
								</tr>
								<tr>
									<td>1488</td>
									<td>13</td>
									<td>2</td>
									<td>3 ряд,<br> 4 место</td>
									<td>2500 руб.</td>
									<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a></td>
								</tr>
								<tr>
									<td>1488</td>
									<td>13</td>
									<td>2</td>
									<td>3 ряд,<br> 4 место</td>
									<td>2500 руб.</td>
									<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a></td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td>ИТОГО</td>
									<td> </td>
									<td> </td>
									<td> </td>
									<td>17500 руб.</td>
									<td><a href="#del_all" class="text-danger" title="Удалить Всё"><span class="glyphicon glyphicon-remove"></span></a></td>
								</tr>
							</tbody>
						</table> 
						
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="set_rowModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
						<h4 class="modal-title">Редактировать</h4>
					</div>
					<div class="modal-body">		
						
					</div>
				</div>
			</div>
		</div>
		
		<div class="container" id="content">
    	<?
			if(CO::AUTH()->admin()){//если админ или юзер то добавить
		?>
			<!-- ПАНЕЛЬ УПРАВЛЕНИЯ -->
			<h1>ПАНЕЛЬ УПРАВЛЕНИЯ</h1>
			<table class="table table-striped table-hover ">
				<thead>
					<tr>
						<th>Номер Заказа</th>
						<th>Клиент</th>
						<th>Номер столика</th>
						<th>Номер места</th>
						<th>Положение</th>
						<th>Стоимость</th>
						<th>Операции</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1488</td>
						<td>ИмяКлиента</td>
						<td>13</td>
						<td>2</td>
						<td>3 ряд, 4 место</td>
						<td>2500 руб.</td>
						<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a href="#set_rowModal"  data-toggle="modal" class="text-primary" title="Редактировать"><span class="glyphicon glyphicon-file"></span></a> </td>
					</tr>
					<tr>
						<td>1488</td>
						<td>ИмяКлиента</td>
						<td>13</td>
						<td>2</td>
						<td>3 ряд, 4 место</td>
						<td>2500 руб.</td>
						<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a href="#set_rowModal"  data-toggle="modal" class="text-primary" title="Редактировать"><span class="glyphicon glyphicon-file"></span></a></td>
					</tr>
					<tr>
						<td>1488</td>
						<td>ИмяКлиента</td>
						<td>13</td>
						<td>2</td>
						<td>3 ряд, 4 место</td>
						<td>2500 руб.</td>
						<td><a href="#del_row" class="text-danger" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a> &nbsp; <a href="#set_rowModal"  data-toggle="modal" class="text-primary" title="Редактировать"><span class="glyphicon glyphicon-file"></span></a></td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>ИТОГО</td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td>7500 руб.</td>
						<td><a href="#del_all" class="text-danger" title="Удалить Всё"><span class="glyphicon glyphicon-remove"></span></td>
					</tr>
				</tbody>
			</table>
			<!-- END ПАНЕЛЬ УПРАВЛЕНИЯ -->
		<?
			}
		?>
		</div>
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="../../assets/libs/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	</body>
</html>