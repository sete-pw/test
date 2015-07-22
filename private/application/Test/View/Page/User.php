<?
	namespace Application\Test\View\Page;

	class User extends \View{

		function content($data){
			if($data['accept']){

				//\CO::RE()->PUSH('js', '/assets/js/user.common.js');
?>
<h1>
	<?=\CO::AUTH()->who()->name?>
</h1>

<div class="col-lg-8">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#profile" data-toggle="tab" aria-expanded="true">Профиль</a></li>
		<li class=""><a href="#security" data-toggle="tab" aria-expanded="false">Безопасность</a></li>
		<li class="disabled"><a>Статистика</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade active in" id="profile">
			

			<form class="form-horizontal col-lg-8" action="/user" method="post">
				<input type="hidden" name="act" value="edit">
				<fieldset>
					<div class="form-group">
						<label for="inputName" class="col-lg-4 control-label">Имя</label>
						<div class="col-lg-8">
							<input type="text" name="name" class="form-control" id="inputName" placeholder="Имя" value="<?=\CO::AUTH()->who()->name?>">
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail" class="col-lg-4 control-label">Email</label>
						<div class="col-lg-8">
							<input type="text" name="email" class="form-control" id="inputEmail" placeholder="Email" value="<?=\CO::AUTH()->who()->email?>" disabled="">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-8 col-lg-offset-4">
							<button type="submit" class="btn btn-primary">Сохранить</button>
						</div>
					</div>
				</fieldset>
			</form>


		</div>
		<div class="tab-pane fade" id="security">
			

			<form class="form-horizontal col-lg-8" action="/user" method="post">
				<input type="hidden" name="act" value="edit">
				<fieldset>
					<div class="form-group">
						<label for="inputPass" class="col-lg-4 control-label">Пароль</label>
						<div class="col-lg-8">
							<input type="password" name="passwd" class="form-control" id="inputPass" placeholder="Пароль">
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassNew" class="col-lg-4 control-label">Новый пароль</label>
						<div class="col-lg-8">
							<input type="password" name="passwd_new" class="form-control" id="inputPassNew" placeholder="Новый пароль">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-8 col-lg-offset-4">
							<button type="submit" class="btn btn-primary">Сохранить</button>
						</div>
					</div>
				</fieldset>
			</form>


		</div>

	</div>
</div>

<?
			}
		}
	}