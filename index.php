<?
$time1 = microtime ( true );
require_once 'li_spider_check.php';
header ( "Content-Type: text/html; charset=utf-8" );
require ('lib.php');
$metatags = '';
$title = '';
$keywords = "";
$description = "";
$tpl = 'main.tpl';
$center = cModules::getContent ( $_GET ["module"] );
new menu ( );
$smarty->assign ( "title", $title );
$smarty->assign ( "keywords", $keywords );
$smarty->assign ( "description", $description );
$smarty->assign ( "content", $center );
$smarty->assign ( "metatags", $metatags );
$time2 = microtime ( true );
$smarty->display ( $tpl );
$time3 = microtime ( true );
?>