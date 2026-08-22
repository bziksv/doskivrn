<?
//Администраторы
//+


class admin {
	
	private $count = 20;
	
	private $items = array ("login" => "input", "email" => "input", "password" => "password", "accessLevel" => "select", "altInfo" => "textsmall" );
	
	private $items_names = array ("login" => "Логин", "email" => "E-mail", "password" => "Пароль", "accessLevel" => "Группа", "altInfo" => "Комментарий" );
	
	private $items_help = array ("login" => "уникальный, 4-30 латинских символов и/или цифр", "email" => "уникальный", "password" => "минимум 6 символов", "accessLevel" => "", "altInfo" => "" );
	
	public $name = 'Администраторы сайта';
	
	public $methods = array ("Список администраторов" => 'listadmins', "Добавление администратора" => 'addadmin', "Удаление администратора" => 'deladmin', "Редактирование администратора" => 'editadmin' );
	
	public function listadmins() {
		global $dbc;
		
		$center = '';
		$listing = '';
		if (isset ( $_GET ["page"] ) and ereg ( "([0-9])", $_GET ["page"] )) {
			$limit_ = $_GET ["page"];
		} else
			$limit_ = 1;
		$limit = $this->count * ($limit_ - 1);
		$dbc->query ( "select count(id) from Admins" );
		$res_count = $dbc->getResult ( 0, 0 );
		$pages = floor ( $res_count / $this->count );
		if (($res_count % $this->count) > 0)
			$pages ++;
		if ($pages > 1) {
			$listing = adminf::getListing ( $pages, $limit_, "#mod=admin&act=listadmins&page=" );
		}
		
		/**
		 * Тайтлы для таблицы
		 */
		$titles = array ("№", "Логин", "Дата создания", "Email", "Кто создал", "Комментарий", "Группа", "Управление" );
		
		$names = array ();
		if (isset ( $_GET ["page"] ) && $_GET ["page"] == 'all') {
			$res1 = $dbc->query ( "select * from Admins order by id DESC", 1 );
		} else {
			$res1 = $dbc->query ( "select * from Admins order by id DESC limit " . $limit . "," . $this->count . "", 1 );
			$GLOBALS ["smarty"]->assign ( 'i_start', $limit );
		}
		
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$obj = mysql_fetch_assoc ( $res1 );
			
			switch ($obj ["dataAdd"]) {
				case 0 :
					$obj ["dataAdd"] = 'н/д';
					break;
				default :
					$obj ["dataAdd"] = date ( "d.m.Y в H:i", $obj ["dataAdd"] );
					break;
			}
			if ($_SESSION ["nick"] == $obj ["login"]) {
				$use = '<a href="#mod=admin&act=editadmin&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=admin&act=listadmins&page={$limit_}" ) . '" title="Перейти к редактирование администратора">Перейти &raquo;</a>';
			} else {
				$use = '<a href="#mod=admin&act=editadmin&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=admin&act=listadmins&page={$limit_}" ) . '" title="Перейти к редактирование администратора">Перейти &raquo;</a><br />			
				<a href="#mod=admin&act=deladmin&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=admin&act=listadmins&page={$limit_}" ) . '" onclick="if (!question(\'Вы уверены, что хотите удалить данного пользователя?\')) return false;" title="Удалить данного пользователя">Удалить &raquo;</a>';
			}
			
			$names [$i] = array ($obj ["login"], $obj ["dataAdd"], $obj ["email"], $dbc->query ( "select login from Admins where id='{$obj["whoAdd"]}'", 6 ), nl2br ( $obj ["altInfo"] ), $dbc->query ( "select nameGroup from AdminsGroups where id='{$obj["accessLevel"]}'", 6 ), $use );
		}
		$center .= adminf::set_tpl_list_1 ( $names, $titles, $listing );
		
		return $center;
	}
	
	public function addadmin() {
		global $dbc, $smarty;
		$center = '';
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
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$y = 0;
					$res1 = $dbc->query ( "select * from AdminsGroups", 1 );
					for($y = 0; $y < mysql_num_rows ( $res1 ); $y ++) {
						$items [$i] [2] [$y] = array ("id" => mysql_result ( $res1, $y, "id" ), "name" => mysql_result ( $res1, $y, "nameGroup" ) );
					}
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=admin&act=addadmin&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2') {
			if ($_POST ["password1"] != $_POST ["password2"]) {
				return 'Пароли не совпадают';
			}
			if (strlen ( $_POST ["password1"] ) < 6) {
				return 'Пароль слишком короткий';
			}
			if (! preg_match ( "/^[0-9A-Za-z]{4,30}$/", $_POST ["login"] )) {
				return 'Некорректный логин';
			}
			if (! eregi ( "^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $_POST ["email"] )) {
				return 'Некорректный E-mail';
			}
			if ($dbc->query ( "select count(id) from Admins where login='" . $_POST ["login"] . "'", 6 ) != 0) {
				return 'Данный логин уже используется';
			}
			if ($dbc->query ( "select count(id) from Admins where email='" . $_POST ["email"] . "'", 6 ) != 0) {
				return 'Данный E-mail уже используется';
			}
			$dbc->query ( "insert into Admins (login, dataAdd, password, email, altInfo, whoadd, accessLevel) 
			values ('" . $_POST ["login"] . "','" . time () . "','" . md5 ( $_POST ["password1"] ) . "',
	          '" . $_POST ["email"] . "','" . htmlspecialchars ( $_POST ["altInfo"], ENT_QUOTES ) . "','" . $dbc->query ( "select id from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"], ENT_QUOTES ) . "'", 6 ) . "','" . $_POST ["accessLevel"] . "')" );
			return '';
		}
		return $center;
	}
	
	public function deladmin() {
		global $dbc;
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$dbc->query ( "select id from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"] ) . "'" );
			if ($dbc->getResult ( 0, 0 ) == $_GET ["uid"]) {
			} else {
				$dbc->query ( "delete from Admins where id='" . $_GET ["uid"] . "'" );
			}
		}
		header ( "Location:?mod=admin&act=listadmins" );
		exit ();
	
	}
	
	public function editadmin() {
		global $dbc, $smarty;
		$center = '';
		
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from Admins where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=admin&act=listadmins" );
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
				if ($key == 'password') {
					$items [$i] [1] ["HELP"] .= "<br />* Оставьте пустым, если не хотите менять.";
				}
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$y = 0;
					$res1 = $dbc->query ( "select * from AdminsGroups", 1 );
					for($y = 0; $y < mysql_num_rows ( $res1 ); $y ++) {
						$items [$i] [2] [$y] = array ("id" => mysql_result ( $res1, $y, "id" ), "name" => mysql_result ( $res1, $y, "nameGroup" ) );
						if ($obj [$key] == $items [$i] [2] [$y] ["id"]) {
							$items [$i] [2] [$y] ["selected"] = "1";
						}
					}
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'action', "?mod=admin&act=editadmin&step=2&uid={$obj["id"]}" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2') {
			
			if ($_POST ["password1"] != '' && $_POST ["password1"] != $_POST ["password2"]) {
				return 'Пароли не совпадают';
			}
			if ($_POST ["password1"] != '' && strlen ( $_POST ["password1"] ) < 6) {
				return 'Пароль слишком короткий';
			}
			if (! preg_match ( "/^[0-9A-Za-z]{4,30}$/", $_POST ["login"] )) {
				return 'Некорректный логин';
			}
			if (! eregi ( "^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $_POST ["email"] )) {
				return 'Некорректный E-mail';
			}
			if ($dbc->query ( "select count(id) from Admins where login='" . $_POST ["login"] . "' and id!='{$obj["id"]}'", 6 ) != 0) {
				return 'Данный логин уже используется';
			}
			if ($dbc->query ( "select count(id) from Admins where email='" . $_POST ["email"] . "' and id!='{$obj["id"]}'", 6 ) != 0) {
				return 'Данный E-mail уже используется';
			}
			if ($_POST ["password1"] == '') {
				$newPass = $obj ["password"];
			} else {
				$newPass = md5 ( $_POST ["password1"] );
			}
			$dbc->query ( "update Admins set 
			login='" . $_POST ["login"] . "', 
			password='" . $newPass . "', 
			email='" . $_POST ["email"] . "', 
			altInfo='" . htmlspecialchars ( $_POST ["altInfo"], ENT_QUOTES ) . "', 
			accessLevel='" . $_POST ["accessLevel"] . "'
			where id='{$obj["id"]}'" );
			return '';
		}
	}

}
$module = new admin ( );

$GLOBALS ['namemodule'] = $module->name;
?>