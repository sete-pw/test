<?
	CO::RE()->errorReporting(E_PARSE | E_ERROR | E_WARNING);

	/**
	 * Выполняем подключение библиотек
	 */

	
	require_once(DIR_ROOT . 'libs/db.php'); // Соединение с MySQL
	require_once(DIR_ROOT . 'libs/authorize.php'); // Авторизация пользователей
	


	/**
	 * Выполняем инициализацию
	 */


	require_once(DIR_ROOT . 'settings/mysql.php'); // Подключение к MySQL
	require_once(DIR_ROOT . 'settings/url.php'); // Пути URL
	require_once(DIR_ROOT . 'settings/authorize.php'); // Авторизация


	/**
	 * Подключаемые элементы
	 */
	CO::RE()->FIX('inc', [
		'template', 'elements/template.php'
	]);



	/**
	 * Выполняем обработку URL
	 */


	CO::RE()->PUSH('header', 'text/html charset=utf-8', 'Content-Type');
	CO::RE()->ACTION();
