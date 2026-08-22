<?
if (! MODULE_SYS)
	die ( "Файл может работать только как модуль!" );
function pages_main() {
	global $dbc, $smarty;
	$GLOBALS ['tpl'] = 'pages.tpl';
	if (isset ( $_GET ["page"] ) and $_GET ["page"] === 'articles') {
		header ( "HTTP/1.1 404 Not Found" );
		header ( "Location:/404.html" );
		exit ();
	}
	if (isset ( $_GET ["page"] ) and preg_match ( "/^[a-zA-Z_0-9]{3,50}$/", $_GET ["page"] )) {
		$dbc->query ( "select * from Pages where link='" . $_GET ["page"] . "'" );
		$obj = $dbc->getObject ();
		if ($obj) {
			$GLOBALS ['title'] = $obj->pageTitle;
			$GLOBALS ['description'] = $obj->pageDescription;
			$GLOBALS ['keywords'] = $obj->pageKeywords;
			$GLOBALS ['smarty']->assign ( 'pagename', $obj->name );
			$GLOBALS ['smarty']->assign ( '_string_ico', $obj->_string_ico );
			if ($obj->link == 'index') {
				$GLOBALS ['tpl'] = 'main.tpl';
				index_page::get_index ();
			}
			/**
			 * Меню
			 */
			$id_m = $dbc->query ( "select id from menu_site where link='/pages/{$_GET ["page"]}/'", 6 );
			$menu = array ();
			if ($id_m !== false && $id_m != 0) {
				$GLOBALS ['smarty']->assign ( 'path', get_path_page($id_m) );
				$res1 = $dbc->query ( "select * from menu_site where parent='$id_m' and status='1' order by num ASC", 1 );
				if (mysql_num_rows ( $res1 ) != 0) {
					for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
						$link = (mysql_result ( $res1, $i, "link" ) == '') ? "#" : mysql_result ( $res1, $i, "link" );
						$menu [$i] = array ($link, mysql_result ( $res1, $i, "name" ) );
					}
					$smarty->assign ( "menu_page", $menu );
				} else {
					$id_m = $dbc->query ( "select parent from menu_site where link='/pages/{$_GET ["page"]}/'", 6 );
					if ($id_m !== false && $id_m != 0) {
						$res1 = $dbc->query ( "select * from menu_site where parent='$id_m' and status='1' order by num ASC", 1 );
						for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
							$link = (mysql_result ( $res1, $i, "link" ) == '') ? "#" : mysql_result ( $res1, $i, "link" );
							$menu [$i] = array ($link, mysql_result ( $res1, $i, "name" ) );
						}
						$smarty->assign ( "menu_page", $menu );
					}
				}
			}
			
			return $obj->pageContent;
		} else {
			header ( "HTTP/1.1 404 Not Found" );
			header ( "Location:/404.html" );
			exit ();
		}
	} else {
		header ( "HTTP/1.1 404 Not Found" );
		header ( "Location:/404.html" );
		exit ();
	}
}


function get_path_page($url){
	global $dbc;
	$str = array ();
	$res1 = $dbc->query ( "SELECT link, name, parent
  FROM menu_site 
  WHERE id = '$url'", 7 );
	if (!$res1){
		return ;
	}
	$link = ($res1["link"] == '') ? "#" : $res1["link"];
	$str[] = array ($link, strip_tags($res1["name"]) );
	if ($res1["parent"] != 0){
		$str = array_merge(get_path_page($res1["parent"]), $str);
	}
	return $str;
	
}

?>