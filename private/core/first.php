<?
	/**
	 * Определяются рабочие директории
	 * Предварительно должны быть определены:
	 * DIR_PRIVATE - директория проекта
	 * DIR_ASSETS - директория публичных ресурсов
	 */

	define('DIR_CORE', DIR_PRIVATE . 'core/');
	define('DIR_APP', DIR_PRIVATE . 'application/');
	define('DIR_LIB', DIR_PRIVATE . 'lib/');

	/**
	 * Подключение компонентов ядра
	 */

	require_once(DIR_CORE . 'co.php');
	require_once(DIR_CORE . 'application.php');
	require_once(DIR_CORE . 'mvc.php');
	require_once(DIR_CORE . 'router.php');

	require_once(DIR_CORE . 'autoload.php');

	/**
	 * Инициализация
	 */
	
	CO::RE();