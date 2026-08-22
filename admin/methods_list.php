<?php
/**
 * Выстраивание методов для админов
 */

header ( "Content-Type: text/html; charset=utf-8" );
include '../lib.php';

/**
 * Исключения
 */
$nexts = array ("admin_menu.php" );

/**
 * смотрим список файлов и делаем список методов
 */

$array = array ();
$i = 0;
if ($handle = opendir ( $_SERVER ['DOCUMENT_ROOT'] . '/admin/mod/' )) {
	while ( false !== ($file = readdir ( $handle )) ) {
		if ($file != "." && $file != ".." && ! in_array ( $file, $nexts )) {
			require_once $_SERVER ['DOCUMENT_ROOT'] . '/admin/mod/' . $file;
			$array [$i] [0] = get_class ( $module );
			$array [$i] [1] = $module->name;
			$array [$i] [2] = array ();
			if (isset ( $module->methods )) {
				$array [$i] [2] = $module->methods;
			}
		
		}
		$i ++;
	}
	closedir ( $handle );
}

foreach ( $array as $value ) {
	foreach ( $value [2] as $key=>$val ) {
		/**
		 * Проверяем есть ли такой метод уже в БД
		 */
		if ($dbc->query ( "select count(id) from admins_list_methods where l_module='{$value[0]}' and l_method='{$val}'", 6 ) == 0) {
			$dbc->query("insert into admins_list_methods (l_module, l_method, l_module_name, l_method_name)
			values ('{$value[0]}','{$val}','{$value [1]}','{$key}')");
		}
	}
}

?>