<?
header ( "Content-Type: text/html; charset=utf-8" );
chdir ( __DIR__ );
include '../lib.php';
include __DIR__ . '/controller.php';
$center = '';

if (isset ( $_GET ["exit"] ) && $_GET ["exit"] == 'true') {
	include 'exit.php';
}

$smarty = new template_admin ( 'admin' );
$smarty->assign ( "sessionid", $sid );
$smarty->assign ( "domain", $_SERVER ['HTTP_HOST'] . "" );
$controller = new controllerAdmin ( );
$smarty->assign ( "content", $center );
$smarty->display ( 'admin_main.tpl' );
?>