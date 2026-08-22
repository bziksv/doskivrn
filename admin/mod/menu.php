<?

class menu {
	
	private $count = 10;
	public $name = 'Меню сайта';
	public $methods = array ("Добавление" => 'addnew', "Удаление" => "del", "Редактирование" => 'edit', "Список меню" => 'listpages' );
	
	private $items = array ("name" => "input", "parent" => "select", "status" => "radio", "link" => "textsmall", "class" => "input", "bottom" => "radio" );
	private $items_names = array ("name" => "Название", "parent" => "Родительская категория", "status" => "Публикация", "link" => "Ссылка", "class" => "Класс", "bottom" => "Нижнее меню" );
	
	public function del() {
		global $dbc;
		$_GET ["uid"] = intval ( $_GET ["uid"] );
		$obj_d = $dbc->query ( "select * from menu_site where id='" . $_GET ["uid"] . "'", 2 );
		if ($obj_d) {
			$dbc->query ( "delete from menu_site where id='" . $_GET ["uid"] . "'" );
			$dbc->query ( "update menu_site set parent='$obj_d->parent' where parent='" . $_GET ["uid"] . "'" );
			$res_ = $dbc->query ( "select * from menu_site where num>$obj_d->num and parent='$obj_d->parent'", 1 );
			for($i = 0; $i < mysql_num_rows ( $res_ ); $i ++) {
				$dbc->query ( "update menu_site set num=num-1 where parent='$obj_d->parent' and id='" . mysql_result ( $res_, $i, "id" ) . "'" );
			}
		}
		header ( "Location:?mod=menu&act=listpages" );
		exit ();
	}
	
	public function listpages() {
		global $dbc;
		if (isset ( $_GET ["action2"] )) {
			if ($_GET ["action2"] == 'changecnt') {
				if (isset ( $_GET ["uid"] ) and preg_match ( "/^[0-9]{1,11}$/", $_GET ["uid"] )) {
					if (isset ( $_GET ["type"] ) and $_GET ["type"] == 'up') {
						$obj_d = $dbc->query ( "select * from menu_site where id='" . $_GET ["uid"] . "'", 2 );
						if (! $obj_d)
							$center .= '<script language="JavaScript">alert(\'Не найдено!\');</script>';
						elseif ($obj_d->num > 1) {
							//Верхнюю двигаем вниз
							$dbc->query ( "update menu_site set num=num+1 where parent='" . $_GET ["parent"] . "' and num='" . ($obj_d->num - 1) . "'" );
							//print "update auto_fotos set cnt=cnt+1 where auto='".$_GET["auto"]."' and cnt='".($obj_d->cnt-1)."'";
							//Текущую фоту двигаем вверх
							$dbc->query ( "update menu_site set num=num-1 where parent='" . $_GET ["parent"] . "' and id='" . $obj_d->id . "'" );
							//print "update auto_fotos set cnt=cnt-1 where auto='".$_GET["auto"]."' and id='".$obj_d->id."'";
						}
					} elseif (isset ( $_GET ["type"] ) and $_GET ["type"] == 'down') {
						$obj_d = $dbc->query ( "select * from menu_site where id='" . $_GET ["uid"] . "'", 2 );
						$max = $dbc->query ( "select max(num) from menu_site where parent='" . $_GET ["parent"] . "'", 6 );
						if (! $obj_d)
							$center .= '<script language="JavaScript">alert(\'Не найдено!\');</script>';
						elseif ($obj_d->num != $max) {
							//Верхнюю двигаем верх
							$dbc->query ( "update menu_site set num=num-1 where parent='" . $_GET ["parent"] . "' and num='" . ($obj_d->num + 1) . "'" );
							//print "update auto_fotos set cnt=cnt+1 where auto='".$_GET["auto"]."' and cnt='".($obj_d->cnt-1)."'";
							//Текущую фоту двигаем вниз
							$dbc->query ( "update menu_site set num=num+1 where parent='" . $_GET ["parent"] . "' and id='" . $obj_d->id . "'" );
							//print "update auto_fotos set cnt=cnt-1 where auto='".$_GET["auto"]."' and id='".$obj_d->id."'";
						}
					}
				}
			}
		}
		/**
		 * Тайтлы для таблицы
		 */
		$titles = array ("Название", "Расположение", "Публикация", "Ссылка", "Управление" );
		$names = array ();
		function create_tree($parent, $left = 0) {
			global $dbc, $titles;
			$center = '';
			$res1 = $dbc->query ( "select * from menu_site where parent='$parent' order by num ASC", 1 );
			for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
				$obj = mysql_fetch_assoc ( $res1 );
				if ($obj ["status"] == '1')
					$status = 'Публикуется';
				else
					$status = 'Скрыт';
				$num = '';
				if ($obj ["num"] != 1)
					$num .= '<div><a href="#mod=menu&act=listpages&action2=changecnt&uid=' . $obj ["id"] . '&parent=' . $obj ["parent"] . '&type=up">Вверх</a></div>';
				$cnt_ = $dbc->query ( "select count(id) from menu_site where num=" . ($obj ["num"] + 1) . "", 6 );
				if ($cnt_ != 0)
					$num .= '<div><a href="#mod=menu&act=listpages&action2=changecnt&uid=' . $obj ["id"] . '&parent=' . $obj ["parent"] . '&type=down">Вниз</a></div>';
				$alt = 'padding-left:' . $left . 'px;';
				if ($obj ["link"] == null)
					$link = '&nbsp;';
				else
					$link = '<a href="' . $obj ["link"] . '" target="new">' . $obj ["link"] . '</a>';
				$names [] = array ('<div align="left" style="' . $alt . '">' . ($i + 1) . '.&nbsp;' . $obj ["name"] . '</div>', $num, $status, $link, '
				<a href="#mod=menu&act=edit&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=menu&act=listpages" ) . '" title="Перейти к редактированию">Перейти &raquo;</a><br />			
				<a href="#mod=menu&act=del&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=menu&act=listpages" ) . '" onclick="if (!question(\'Вы уверены, что хотите удалить данный пункт?\')) return false;" title="Удалить текущий пункт">Удалить &raquo;</a>' );
				$cnt = $dbc->query ( "select count(id) from menu_site where parent='{$obj["id"]}'", 6 );
				if ($cnt > 0)
					$names = array_merge ( $names, create_tree ( $obj ["id"], $left + 12 ) );
			}
			
			return $names;
		}
		$names = create_tree ( 0, 2 );
		return adminf::set_tpl_list_2 ( $names, $titles, "" );
	}
	
	public function addnew() {
		global $dbc, $smarty;
		function create_tree($parent, $left) {
			global $dbc;
			$res1 = $dbc->query ( "select * from menu_site where parent='$parent' order by num ASC", 1 );
			$array = array ();
			for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
				$obj = mysql_fetch_object ( $res1 );
				$array [] = array ("id" => $obj->id, "name" => $left . '' . $obj->name );
				$cnt = $dbc->query ( "select count(id) from menu_site where parent='$obj->id'", 6 );
				if ($cnt > 0)
					$array = array_merge ( $array, create_tree ( $obj->id, $left . '&nbsp;&nbsp;&nbsp;' ) );
			}
			return $array;
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
				$items [$i] [1] ["VALUE"] = '';
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$items [$i] [2] [] = array ("id" => 0, "name" => "--без раздела--" );
					$items [$i] [2] = array_merge ( $items [$i] [2], create_tree ( 0, '&nbsp;' ) );
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=menu&act=addnew&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2') {
			if (trim ( $_POST ["name"] ) == '')
				return 'Необходимо ввести название';
			if (trim ( $_POST ["link"] ) == '')
				return 'Необходимо ввести ссылку';
			
			$num = $dbc->query ( "select max(num) from menu_site where parent='" . intval ( $_POST ["parent"] ) . "'", 6 ) + 1;
			$dbc->query ( "insert into menu_site (name,parent,num,status,link,class,bottom) values (
			'" . htmlspecialchars ( $_POST ["name"], ENT_QUOTES ) . "',
			'" . intval ( $_POST ["parent"] ) . "',
			'$num',
			'" . intval ( $_POST ["status"] ) . "',
			'" . htmlspecialchars ( $_POST ["link"], ENT_QUOTES ) . "',
			'" . htmlspecialchars ( $_POST ["class"], ENT_QUOTES ) . "',
			'" . htmlspecialchars ( $_POST ["bottom"], ENT_QUOTES ) . "')" );
			
			return '';
		}
	}
	
	public function edit() {
		global $dbc, $smarty;
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from menu_site where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=articles&act=listpages" );
		}
		
		function create_tree($parent, $left, $par) {
			global $dbc;
			$res1 = $dbc->query ( "select * from menu_site where parent='$parent' order by num ASC", 1 );
			$array = array ();
			for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
				$obj = mysql_fetch_object ( $res1 );
				$array [] = array ("id" => $obj->id, "name" => $left . '' . $obj->name );
				if ($par == $array [count($array)-1] ["id"]) {
					$array [count($array)-1] ["selected"] = "1";
				}
				$cnt = $dbc->query ( "select count(id) from menu_site where parent='$obj->id'", 6 );
				if ($cnt > 0) {
					$array = array_merge ( $array, create_tree ( $obj->id, $left . '&nbsp;&nbsp;&nbsp;', $par ) );
				}
			}
			return $array;
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
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$items [$i] [2] [] = array ("id" => 0, "name" => "--без раздела--" );
					$items [$i] [2] = array_merge ( $items [$i] [2], create_tree ( 0, '&nbsp;', $obj [$key] ) );
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'action', "?mod=menu&act=edit&step=2&uid={$obj["id"]}" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2') {
			if (trim ( $_POST ["name"] ) == '')
				return 'Необходимо ввести название';
			if (trim ( $_POST ["link"] ) == '')
				return 'Необходимо ввести ссылку';
			
			$dbc->query ( "update menu_site set 
			name='" . htmlspecialchars ( $_POST ["name"], ENT_QUOTES ) . "',
			parent='" . intval ( $_POST ["parent"] ) . "',
			status='" . intval ( $_POST ["status"] ) . "',
			link='" . htmlspecialchars ( $_POST ["link"], ENT_QUOTES ) . "',
			bottom='" . htmlspecialchars ( $_POST ["bottom"], ENT_QUOTES ) . "',
			class='" . htmlspecialchars ( $_POST ["class"], ENT_QUOTES ) . "'
			where id='{$obj["id"]}'" );
			
			return '';
		}
	}

}
$module = new menu ( );

$GLOBALS ['namemodule'] = $module->name;
?>