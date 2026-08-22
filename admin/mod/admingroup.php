<?
//Группы администраторов


//+


class admingroup {
	
	private $count = 20;
	private $items = array ("nameGroup" => "input", "methods" => "checkbox_array" );
	private $items_names = array ("nameGroup" => "Название группы", "methods" => "Доступ к действиям модулей" );
	private $items_help = array ("nameGroup" => "", "methods" => "Выберите методы, к которым группа получает доступ" );
	public $name = 'Группы администраторов';
	public $methods = array ("Список групп" => 'listadmins', "Добавление новой" => 'addadmin', "Удаление группы" => 'deladmin', "Редактирование прав группы" => 'editadmin' );
	public function listadmins() {
		global $dbc;
		
		$center = '';
		$listing = '';
		if (isset ( $_GET ["page"] ) and ereg ( "([0-9])", $_GET ["page"] )) {
			$limit_ = $_GET ["page"];
		} else
			$limit_ = 1;
		$limit = $this->count * ($limit_ - 1);
		$dbc->query ( "select count(id) from AdminsGroups" );
		$res_count = $dbc->getResult ( 0, 0 );
		$pages = floor ( $res_count / $this->count );
		if (($res_count % $this->count) > 0)
			$pages ++;
		if ($pages > 1) {
			$listing = adminf::getListing ( $pages, $limit_, "#mod=admingroup&act=listadmins&page=" );
		}
		
		/**
		 * Тайтлы для таблицы
		 */
		$titles = array ("№", "Название", "Кол-во в группе", "Управление" );
		
		$names = array ();
		if (isset ( $_GET ["page"] ) && $_GET ["page"] == 'all') {
			$res1 = $dbc->query ( "select * from AdminsGroups order by id DESC", 1 );
		} else {
			$res1 = $dbc->query ( "select * from AdminsGroups order by id DESC limit " . $limit . "," . $this->count . "", 1 );
			$GLOBALS ["smarty"]->assign ( 'i_start', $limit );
		}
		
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$obj = mysql_fetch_assoc ( $res1 );
			$use = '<a href="#mod=admingroup&act=editadmin&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=admingroup&act=listadmins&page={$limit_}" ) . '" title="Перейти к редактирование администратора">Перейти &raquo;</a><br />			
				<a href="#mod=admingroup&act=deladmin&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=admingroup&act=listadmins&page={$limit_}" ) . '" onclick="if (!question(\'Вы уверены, что хотите удалить данную группу?\')) return false;" title="Удалить данного пользователя">Удалить &raquo;</a>';
			
			$names [$i] = array ($obj ["nameGroup"], $dbc->query ( "select count(id) from Admins where accessLevel='{$obj ["id"]}'", 6 ), $use );
		}
		$center .= adminf::set_tpl_list_1 ( $names, $titles, $listing );
		
		return $center;
	}
	
	public function addadmin() {
		global $dbc, $smarty;
		if (! isset ( $_GET ["step"] )) {
			
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			foreach ( $this->items as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $this->items_names [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = '';
				$items [$i] [1] ["HELP"] = $this->items_help [$key];
				if ($items [$i] [0] == 'checkbox_array') {
					//Категории
					$items [$i] [2] = array ();
					$res1 = $dbc->query ( "SELECT id, l_module_name, l_method_name, l_module
					FROM `admins_list_methods`
					ORDER BY l_module_name ASC, l_method_name ASC", 1 );
					$cat = '';
					$u = - 1;
					for($y = 0; $y < mysql_num_rows ( $res1 ); $y ++) {
						if ($cat != mysql_result ( $res1, $y, "l_module_name" )) {
							$cat = mysql_result ( $res1, $y, "l_module_name" );
							$u ++;
						}
						$items [$i] [2] [$u] [0] = array (mysql_result ( $res1, $y, "l_module_name" ), mysql_result ( $res1, $y, "l_module" ) );
						$items [$i] [2] [$u] [1] [] = array ("id" => mysql_result ( $res1, $y, "id" ), "name" => mysql_result ( $res1, $y, "l_method_name" ) );
					}
				}
			}
			
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=admingroup&act=addadmin&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2') {
			if (strlen ( trim ( $_POST ["nameGroup"] ) ) == 0) {
				return 'Необходимо ввести название группы';
			}
			
			$group_id = $dbc->query ( "insert into AdminsGroups (nameGroup) values ('" . htmlspecialchars ( $_POST ["nameGroup"], ENT_QUOTES ) . "')", 4 );
			
			foreach ( $_POST ["methods"] as $value ) {
				$dbc->query ( "insert into admins_methods_group (l_group, l_method) values ('{$group_id}', '{$value}')" );
			}
			
			return '';
		}
	}
	
	public function deladmin() {
		global $dbc;
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$dbc->query ( "select accesslevel from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"] ) . "'" );
			if ($dbc->getResult ( 0, 0 ) == $_GET ["uid"]) {
			} else {
				$dbc->query ( "delete from AdminsGroups where id='" . $_GET ["uid"] . "'" );
				$dbc->query ( "delete from admins_methods_group where l_group='" . $_GET ["uid"] . "'" );
			}
		}
		header ( "Location:?mod=admingroup&act=listadmins" );
		exit ();
	
	}
	
	public function editadmin() {
		global $dbc, $smarty;
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from AdminsGroups where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=admingroup&act=listadmins" );
		}
		
		if (! isset ( $_GET ["step"] )) {
			
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			foreach ( $this->items as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $this->items_names [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = str_replace ( '"', "&quot;", $obj [$key] );
				$items [$i] [1] ["HELP"] = $this->items_help [$key];
				if ($items [$i] [0] == 'checkbox_array') {
					//Категории
					$items [$i] [2] = array ();
					$res1 = $dbc->query ( "SELECT id, l_module_name, l_method_name, l_module
					FROM `admins_list_methods`
					ORDER BY l_module_name ASC, l_method_name ASC", 1 );
					$cat = '';
					$u = - 1;
					$p = 0;
					for($y = 0; $y < mysql_num_rows ( $res1 ); $y ++) {
						if ($cat != mysql_result ( $res1, $y, "l_module_name" )) {
							$cat = mysql_result ( $res1, $y, "l_module_name" );
							$u ++;
							$p = 0;
						}
						$items [$i] [2] [$u] [0] = array (mysql_result ( $res1, $y, "l_module_name" ), mysql_result ( $res1, $y, "l_module" ) );
						$items [$i] [2] [$u] [1] [$p] = array ("id" => mysql_result ( $res1, $y, "id" ), "name" => mysql_result ( $res1, $y, "l_method_name" ) );
						if ($dbc->query ( "select count(id) from admins_methods_group where l_group='{$obj["id"]}' and l_method='" . mysql_result ( $res1, $y, "id" ) . "'", 6 ) != 0) {
							$items [$i] [2] [$u] [1] [$p] ["selected"] = "selected";
						}
						$p ++;
					}
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'action', "?mod=admingroup&act=editadmin&step=2&uid={$obj["id"]}" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2') {
			if (strlen ( trim ( $_POST ["nameGroup"] ) ) == 0) {
				return 'Необходимо ввести название группы';
			}
			
			$dbc->query ( "update AdminsGroups set nameGroup='" . htmlspecialchars ( $_POST ["nameGroup"], ENT_QUOTES ) . "' where id='{$obj["id"]}'" );
			
			$dbc->query ( "delete from admins_methods_group where l_group='{$obj["id"]}'" );
			foreach ( $_POST ["methods"] as $value ) {
				$dbc->query ( "insert into admins_methods_group (l_group, l_method) values ('{$obj["id"]}', '{$value}')" );
			}
			
			return '';
		}
	}

}
$module = new admingroup ( );

$GLOBALS ['namemodule'] = $module->name;
?>