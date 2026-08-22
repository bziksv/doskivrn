<?
//Ставим локаль на utf
setlocale ( LC_ALL, 'ru_RU.UTF-8' );
//Файл конфигурации
include "configure.php";
//Автоподгрузка классов
function __autoload($class_name) {
	require_once $GLOBALS ["path_conf"] . "class." . $class_name . '.php';
}
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
	setcookie ( "_sid", $sid, false, '/', false, 0 );
}
$sid = session_id ();

include $path_modules . 'module.feedback.php';
feedback_handle_request ();
?>