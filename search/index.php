<?php
/*******************************************
 * Sphider Version 1.3.x
 * This program is licensed under the GNU GPL.
 * By Ando Saabas          ando(a t)cs.ioc.ee
 ********************************************/
define('MODULE_SYS', true);
require_once dirname(__DIR__) . '/configure.php';

function __autoload($class_name) {
	require_once $_SERVER ['DOCUMENT_ROOT'] . "/classes/class." . $class_name . '.php';
}

error_reporting ( E_ALL );
$include_dir = "./include";
include ("$include_dir/commonfuncs.php");
//extract(getHttpVars());


if (isset ( $_GET ['query'] )) {
	$query = $_GET ['query'];
} else {
	$_GET ['query'] = '';
}
if (isset ( $_GET ['search'] ))
	$search = $_GET ['search'];
if (isset ( $_GET ['domain'] ))
	$domain = $_GET ['domain'];
if (isset ( $_GET ['type'] ))
	$type = $_GET ['type'];
if (isset ( $_GET ['catid'] ))
	$catid = $_GET ['catid'];
if (isset ( $_GET ['category'] ))
	$category = $_GET ['category'];
if (isset ( $_GET ['results'] ))
	$results = $_GET ['results'];
if (isset ( $_GET ['start'] ))
	$start = $_GET ['start'];
if (isset ( $_GET ['adv'] ))
	$adv = $_GET ['adv'];

$include_dir = "./include";
$template_dir = "./templates";
$settings_dir = "./settings";
$language_dir = "./languages";

require_once ("$settings_dir/database.php");
require_once ("$language_dir/en-language.php");
require_once ("$include_dir/searchfuncs.php");
require_once ("$include_dir/categoryfuncs.php");

include "$settings_dir/conf.php";

//include "$template_dir/$template/header.html";
include "$language_dir/$language-language.php";

if ($type != "or" && $type != "and" && $type != "phrase") {
	$type = "and";
}

if (preg_match ( "/[^a-z0-9-.]+/", $domain )) {
	$domain = "";
}

if ($results != "") {
	$results_per_page = $results;
}

if (get_magic_quotes_gpc () == 1) {
	$query = stripslashes ( $query );
}

if (! is_numeric ( $catid )) {
	$catid = "";
}

if (! is_numeric ( $category )) {
	$category = "";
}

if ($catid && is_numeric ( $catid )) {
	
	$tpl_ ['category'] = sql_fetch_all ( 'SELECT category FROM ' . $mysql_table_prefix . 'categories WHERE category_id=' . ( int ) $_REQUEST ['catid'] );
}

$count_level0 = sql_fetch_all ( 'SELECT count(*) FROM ' . $mysql_table_prefix . 'categories WHERE parent_num=0' );
$has_categories = 0;

if ($count_level0) {
	$has_categories = $count_level0 [0] [0];
}

function getmicrotime() {
	list ( $usec, $sec ) = explode ( " ", microtime () );
	return (( float ) $usec + ( float ) $sec);
}

function poweredby() {
	global $sph_messages;
	//If you want to remove this, please donate to the project at http://www.sphider.eu/donate.php


}

function saveToLog($query, $elapsed, $results) {
	global $mysql_table_prefix;
	if ($results == "") {
		$results = 0;
	}
	$query = "insert into " . $mysql_table_prefix . "query_log (query, time, elapsed, results) values ('$query', now(), '$elapsed', '$results')";
	mysql_query ( $query );
	
	echo mysql_error ();

}

include $_SERVER ['DOCUMENT_ROOT'] . "/classes/class.smarty.php";
include $path_conf . "class.global.php";
$smarty = new template_main ( 'default' );

ob_start ();

//require_once ("$template_dir/$template/search_form.html");

switch ($search) {
	case 1 :
		
		if (! isset ( $results )) {
			$results = "";
		}
		$search_results = get_search_results ( $query, $start, $category, $type, $results, $domain );
		require ("$template_dir/$template/search_results.html");
		break;
	default :
		if ($show_categories) {
			if ($_REQUEST ['catid'] && is_numeric ( $catid )) {
				$cat_info = get_category_info ( $catid );
			} else {
				$cat_info = get_categories_view ();
			}
			require ("$template_dir/$template/categories.html");
		}
		break;
}

$content = ob_get_contents ();
ob_end_clean ();

$dbc = new database ( );
$dbc->setConnectValue ( $dbhost, $dbname, $dbuser, $dbpass );



$smarty->assign ( "content", $content );
$smarty->assign ( "pagename", "Поиск" );

if (trim ( htmlspecialchars ( $_GET ["query"], ENT_QUOTES ) ) != $last_words [0] [0] && trim ( htmlspecialchars ( $_GET ["query"], ENT_QUOTES ) ) != '') {
	$dbc->query ( "update search_last set word_q='" . urldecode ( htmlspecialchars ( $_GET ["query"], ENT_QUOTES ) ) . "' where num='1'" );
	$dbc->query ( "update search_last set word_q='" . $last_words [0] [0] . "' where num='2'" );
	$dbc->query ( "update search_last set word_q='" . $last_words [1] [0] . "' where num='3'" );
} elseif ($last_words [0] [0] == '') {
	$dbc->query ( "update search_last set word_q='" . urldecode ( htmlspecialchars ( $_GET ["query"], ENT_QUOTES ) ) . "' where num='1'" );
	$dbc->query ( "update search_last set word_q='" . $last_words [0] [0] . "' where num='2'" );
	$dbc->query ( "update search_last set word_q='" . $last_words [1] [0] . "' where num='3'" );
}
new menu ( );

//Вопрос для формы отправки сообщения
$q1 = rand ( 1, 9 );
$q2 = rand ( 1, 9 );
if (isset($_SESSION ["randomQuest"]))
	$_SESSION ["randomQuestold"] = $_SESSION ["randomQuest"];
$_SESSION ["randomQuest"] = $q1 + $q2;
$smarty->assign ( "quest", $q1 . "+" . $q2 );

header ( "Content-Type: text/html; charset=utf-8" );
$smarty->display ( "pages.tpl" );
//require_once $_SERVER['DOCUMENT_ROOT'].'/lib.php';
//include "$template_dir/$template/footer.html";
?>
