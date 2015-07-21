<?
	namespace Application\Test;

	class Test extends \Application{
		private $router;

		private function router(){
			$this->router = new \Router();

			$this->router->push('/^\/?$/', function($args){
				$out = new Controller\Page();
				echo $out->index();
			});
			$this->router->push('/^test$/', function($args){
				$out = new Controller\Page();
				echo $out->test();
			});

			$this->router->push('/^api\/v(?P<version>.*)\/(?P<class>.*)\.(?P<method>.*)/', function($args){
				$out = new Controller\API();
				echo $out->index([
					'vaersion' => $args['version'],
					'class' => $args['class'],
					'method' => $args['method']
				]);
			});

			$this->router->push('/^login/', function($args){
				\CO::AUTH()->login(
					\CO::RE()->post['email'],
					\CO::RE()->post['passwd']
				);

				\CO::RE()->redirect('/');
			});

			$this->router->push('/^logout/', function($args){
				\CO::AUTH()->logout();

				\CO::RE()->redirect('/');
			});

			$this->router->push('/^.*/', function($args){
				echo '404. Not found!';
			});

			$this->router->start(\CO::RE()->url);
		}

		function main(){
			\CO::RE()->header('content-type', 'text/html; charset=utf-8');

			\CO::SQL(
				new \DB\SQLi()
			)->connect(
				'188.120.227.83',
				'root',
				'kolkol123',
				'test_sete_pw'
			)->query(
				"set names utf8;"
			);

			\CO::AUTH(new Model\Auth('039hg0whas9w8bp3wg'));

			$this->router();
		}
	}