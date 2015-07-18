<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="windows-1251">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>������� ������</title>

    <!-- Bootstrap -->
    <link href="../../assets/libs/bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
	body { padding-top: 50px; }
	</style>
	
  </head>
  <body>
  
  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">��������</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav nav-pills">
				<li class="active"><a href="#"><span class="glyphicon glyphicon-home"></span> �������<span class="sr-only">(current)</span></a></li>
				<li><a href="#shop">�������� ������</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right nav-pills">
				<!-- ���� �� ���� ������ ���� -->
				<? 
					if(isset(CO::RE()->get['login'])){
				?>
				<li><a href="#bin" data-toggle="modal"><span class="glyphicon glyphicon-shopping-cart"></span> ������� <span class="badge">0</span></a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> ����������<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#settings"><span class="glyphicon glyphicon-cog"></span> ���������</a></li>
				<?	
						if(isset(CO::RE()->get['admin'])){//���� ����� �� �������� ����� ����
				?>				
				<li><a href="#admin"><span class="glyphicon glyphicon-wrench"></span> ����������</a></li>
				<?
						}
				?>
						<li class="divider"></li>
						<li><a href="#logout"><span class="glyphicon glyphicon-remove"></span> �����</a></li>
					</ul>
				</li>
				<?
					} else {
				?>
				<li><a href="#login" data-toggle="modal">�����</a></li>
				<? 
					} 
				?>
			</ul>
		</div>
	</div>
</nav>
    
<div class="modal fade" id="login" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
				<h4 class="modal-title">����</h4>
			</div>
			<div class="modal-body">		
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label for="inputEmail" class="col-lg-2 control-label">Email</label>
							<div class="col-lg-10">
								<input class="form-control" id="inputEmail" placeholder="Email" type="text">
							</div>
						</div>
						<div class="form-group">
							<label for="inputPassword" class="col-lg-2 control-label">������</label>
							<div class="col-lg-10">
								<input class="form-control" id="inputPassword" placeholder="������" type="password">
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-10 col-lg-offset-2">
								<button type="submit" class="btn btn-primary"> ����� </button>
								<a class="btn btn-info" href="#reg">�����������</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="bin" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
				<h4 class="modal-title">�������</h4>
			</div>
			<div class="modal-body">		
				<?/*����� ������; ����� �������; ����� �����; ��������� (���� ������ ���, ��������� ����); ���������; ������ �������*/?>
				<table class="table table-striped table-hover ">
					<thead>
						<tr>
							<th>����� ������</th>
							<th>����� �������</th>
							<th>����� �����</th>
							<th>���������</th>
							<th>���������</th>
							<th>��������</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1488</td>
							<td>13</td>
							<td>2</td>
							<td>3 ���, 4 �����</td>
							<td>2500 �.</td>
							<td><a href="#del_row" class="text-danger" title="�������"><span class="glyphicon glyphicon-remove"></span></td>
						</tr>
						<tr>
							<td>1488</td>
							<td>13</td>
							<td>2</td>
							<td>3 ���, 4 �����</td>
							<td>2500 �.</td>
							<td><a href="#del_row" class="text-danger" title="�������"><span class="glyphicon glyphicon-remove"></span></td>
						</tr>
						<tr>
							<td>1488</td>
							<td>13</td>
							<td>2</td>
							<td>3 ���, 4 �����</td>
							<td>2500 �.</td>
							<td><a href="#del_row" class="text-danger" title="�������"><span class="glyphicon glyphicon-remove"></span></td>
						</tr>
						<tr>
							<td>1488</td>
							<td>13</td>
							<td>2</td>
							<td>3 ���, 4 �����</td>
							<td>2500 �.</td>
							<td><a href="#del_row" class="text-danger" title="�������"><span class="glyphicon glyphicon-remove"></span></td>
						</tr>
						<tr>
							<td>1488</td>
							<td>13</td>
							<td>2</td>
							<td>3 ���, 4 �����</td>
							<td>2500 �.</td>
							<td><a href="#del_row" class="text-danger" title="�������"><span class="glyphicon glyphicon-remove"></span></td>
						</tr>
						<tr>
							<td>1488</td>
							<td>13</td>
							<td>2</td>
							<td>3 ���, 4 �����</td>
							<td>2500 �.</td>
							<td><a href="#del_row" class="text-danger" title="�������"><span class="glyphicon glyphicon-remove"></span></td>
						</tr>
						<tr>
							<td>1488</td>
							<td>13</td>
							<td>2</td>
							<td>3 ���, 4 �����</td>
							<td>2500 �.</td>
							<td><a href="#del_row" class="text-danger" title="�������"><span class="glyphicon glyphicon-remove"></span></td>
						</tr>
					</tbody>
				</table> 
				
			</div>
		</div>
	</div>
</div>

<div class="content" style="height:1000px;">
  
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../assets/libs/bootstrap-3.3.5/js/bootstrap.min.js"></script>

  </body>
</html>