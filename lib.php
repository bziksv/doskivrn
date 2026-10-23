<?
//Ставим локаль на utf
setlocale ( LC_ALL, 'ru_RU.UTF-8' );
//Файл конфигурации
include "configure.php";
// PHP 7+/8: ext/mysql убран — shim через mysqli
if (!function_exists('mysql_connect')) {
	$__mysql_shim = $global_path . '.local/mysql.php';
	if (is_readable($__mysql_shim)) {
		require_once $__mysql_shim;
	}
}
//Автоподгрузка классов
spl_autoload_register(function ($class_name) {
	require_once $GLOBALS ["path_conf"] . "class." . $class_name . '.php';
});
define ( "MODULE_SYS", true );
//Подключение необходимых компонентов
//include $path_conf . "class.trigger.php";
include $path_conf . "class.global.php";
//error_reporting(E_ALL);
include $path_conf . "class.smarty.php";
//Создание БД
$dbc = new database ( );
$dbc->setConnectValue ( $dbhost, $dbname, $dbuser, $dbpass );
//Убиваем переменные для БД, чтобы никому не достались
//unset($dbhost,$dbuser,$dbpass,$dbname);
//Запуск шаблона
$smarty = new template_main ( 'default' );
//Запуск сессии
if (isset ( $_GET ["sid"] )) {
	session_id ( $_GET ["sid"] );
} elseif (isset ( $_COOKIE ["_sid"] )) {
	session_id ( $_COOKIE ["_sid"] );
}
session_start ();
if (! isset ( $_COOKIE ["_sid"] )) {
	$sid = session_id ();
	setcookie ( "_sid", $sid, 0, '/', '', false, false );
}
$sid = session_id ();

include $path_modules . 'module.feedback.php';
feedback_handle_request ();
?>