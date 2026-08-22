<?php

if (! function_exists('mysql_connect')) {
	$mysql_shim_paths = array(
		dirname(dirname(__DIR__)) . '/.local/mysql.php',
		isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/.local/mysql.php' : null,
	);
	foreach ($mysql_shim_paths as $mysql_shim) {
		if ($mysql_shim && file_exists($mysql_shim)) {
			require_once $mysql_shim;
			break;
		}
	}
}

require_once dirname(dirname(__DIR__)) . '/configure.php';

$database = $dbname;
$mysql_user = $dbuser;
$mysql_password = $dbpass;
$mysql_host = $dbhost;
$mysql_table_prefix = 'search_';

$success = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$success) {
	die('<b>Cannot connect to database, check if username, password and host are correct.</b>');
}

$success = mysql_select_db($database);
if (!$success) {
	print '<b>Cannot choose database, check if database name is correct.';
	die();
}

mysql_query('set character_set_client=\'utf8\'');
mysql_query('set character_set_results=\'utf8\'');
mysql_query('set collation_connection=\'utf8_general_ci\'');
