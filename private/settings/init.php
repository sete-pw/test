<?
	CO::RE()->errorReporting(E_PARSE | E_ERROR | E_WARNING);

	/**
	 * ��������� ����������� ���������
	 */

	
	require_once(DIR_ROOT . 'libs/db.php'); // ���������� � MySQL
	require_once(DIR_ROOT . 'libs/authorize.php'); // ����������� �������������
	


	/**
	 * ��������� �������������
	 */


	require_once(DIR_ROOT . 'settings/mysql.php'); // ����������� � MySQL
	require_once(DIR_ROOT . 'settings/url.php'); // ���� URL
	require_once(DIR_ROOT . 'settings/authorize.php'); // �����������


	/**
	 * ��������� ��������� URL
	 */


	CO::RE()->PUSH('header', 'text/html charset=windows-1251', 'Content-Type');
	CO::RE()->ACTION();
