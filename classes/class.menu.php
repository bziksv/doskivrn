<?php
class menu {
	public function __construct() {
		global $dbc, $smarty;
		$menu = array ();
		$res1 = $dbc->query ( "select * from menu_site where parent='0' and status='1' order by num ASC", 1 );
		$l = false;
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$_this = false;
			$link = (mysql_result ( $res1, $i, "link" ) == '') ? "#" : mysql_result ( $res1, $i, "link" );
			$menu [$i] = array ($link, mysql_result ( $res1, $i, "name" ) );
			$menu [$i]["class"] = mysql_result ( $res1, $i, "class" ) ;
			$menu [$i]["bottom"] = mysql_result ( $res1, $i, "bottom" ) ;
			$module = isset ( $_GET ["module"] ) ? $_GET ["module"] : '';
			$page = isset ( $_GET ["page"] ) ? $_GET ["page"] : '';
			if ($module == 'pages') {
				if ("/pages/{$page}/" == $link) {
					$_this = true;
				}
			} elseif ($module !== '' && preg_match ( "/^\/{$module}/", $link )) {
				$_this = true;
			
			}
			
			$res2 = $dbc->query ( "select * from menu_site where parent='" . mysql_result ( $res1, $i, "id" ) . "' and status='1' order by num ASC", 1 );
			if (mysql_num_rows ( $res2 ) > 0) {
				$menu [$i] ["childs"] = array ();
				for($y = 0; $y < mysql_num_rows ( $res2 ); $y ++) {
					$link = (mysql_result ( $res2, $y, "link" ) == '') ? "#" : mysql_result ( $res2, $y, "link" );
					$menu [$i] ["childs"] [$y] = array ($link, mysql_result ( $res2, $y, "name" ) );
					if ($module == 'pages') {
						if ("/pages/{$page}/" == $link) {
							$menu [$i] ["childs"] [$y] [] = "1";
							$_this = true;
						}
					} elseif (preg_match ( "/^" . str_replace ( "/", "\/", $_SERVER ['REQUEST_URI'] ) . "$/", $link )) {
						$menu [$i] ["childs"] [$y] [] = "1";
						$_this = true;
					
					}
					
					$res3 = $dbc->query ( "select * from menu_site where parent='" . mysql_result ( $res2, $y, "id" ) . "' and status='1' order by num ASC", 1 );
					if (mysql_num_rows ( $res3 ) > 0) {
						$menu [$i] ["childs"] [$y] ["childs"] = array ();
						for($t = 0; $t < mysql_num_rows ( $res3 ); $t ++) {
							$link = (mysql_result ( $res3, $t, "link" ) == '') ? "#" : mysql_result ( $res3, $t, "link" );
							$menu [$i] ["childs"] [$y] ["childs"] [$t] = array ($link, mysql_result ( $res3, $t, "name" ) );
							if ($_GET ["module"] == 'pages') {
								if ("/pages/{$_GET["page"]}/" == $link) {
									$menu [$i] ["childs"] [$y] ["childs"] [$t] [] = "1";
									$menu [$i] ["childs"] [$y] [] = "1";
									$_this = true;
								}
							} elseif (preg_match ( "/^" . str_replace ( "/", "\/", $_SERVER ['REQUEST_URI'] ) . "$/", $link )) {
								$menu [$i] ["childs"] [$y] ["childs"] [$t] [] = "1";
								$menu [$i] ["childs"] [$y] [] = "1";
								$_this = true;
							
							}
						}
					}
				}
			
			}
			if ($_this) {
				if ($l !== false) {
					unset ( $menu [$l] [2] );
				}
				$menu [$i] [] = "1";
				if (isset($menu [$i] ["childs"]))
					$sub_menu = $menu [$i] ["childs"];
				
				$l = $i;
			}
		
		}
		//				print '<pre>';
		//				print_r ( $sub_menu );
		//				print_r ( $menu );
		//				print '</pre>';
		if (isset ( $sub_menu )) {
			$smarty->assign ( "sub_menu", $sub_menu );
			unset ( $sub_menu );
		}
		
		$smarty->assign ( "menu", $menu );
	}

}
?>