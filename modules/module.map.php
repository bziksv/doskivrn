<?php
if (! MODULE_SYS)
	die ( "Файл может работать только как модуль!" );
function map_main() {
	global $dbc;
	$GLOBALS ['tpl'] = 'pages.tpl';
	$GLOBALS ['smarty']->assign ( 'pagename', "Карта сайта" );
	$center = '';
	
	//	******Динамическое меню******
	function createsubmenu_($idparent, $vis = '') {
		global $dbc;
		$res1 = $dbc->query ( "select * from menu_site where parent='$idparent' order by num ASC", 1 );
		if (mysql_num_rows ( $res1 ) == 0)
			return '';
		$menu = '';
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			if (mysql_result ( $res1, $i, "link" ) == '/gallery/')
				continue;
			$link = (mysql_result ( $res1, $i, "link" ) == '') ? "#" : mysql_result ( $res1, $i, "link" );
			$menu .= '<div>' . $vis . '<a href="' . $link . '">' . mysql_result ( $res1, $i, "name" ) . '</a></div>';
			$menu .= createsubmenu_ ( mysql_result ( $res1, $i, "id" ), $vis . '&nbsp;&nbsp;&nbsp;&nbsp;' );
		}
		return $menu;
	}
	$menu = '';
	$res1 = $dbc->query ( "select * from menu_site where parent='0' order by num ASC", 1 );
	for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
		$link = (mysql_result ( $res1, $i, "link" ) == '') ? "#" : mysql_result ( $res1, $i, "link" );
		$menu .= '<div><a href="' . $link . '">' . mysql_result ( $res1, $i, "name" ) . '</a></div>';
		$menu .= "" . createsubmenu_ ( mysql_result ( $res1, $i, "id" ), "&nbsp;&nbsp;&nbsp;&nbsp;" ) . "";
	}
	$center .= $menu;
	$center .= '';
	

	
	$center .= '';
	
	
	
	$center .= '';
	return $center;
}
?>