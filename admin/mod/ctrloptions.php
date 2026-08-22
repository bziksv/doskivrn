<?
//Статические страницы
//+


class ctrloptions {
	
	private $count = 25;
	public $name = 'Управление опциями';
	
	private $items = array ("t_name" => "input", "t_source" => "input", "t_type" => "input", "t_type_edit" => "input", "t_type_edit_alt" => "input" );
	private $items_names = array ("t_name" => "Название типа данных", "t_source" => "Идентификатор (КАПС)", "t_type" => "тип данных для БД (int, text итп)", "t_type_edit" => "Тип для редактирования", "t_type_edit_alt" => "Дополнительная информация для поля" );
	
	public $methods = array ("Список свойств" => 'listoptions', "Добавление свойства" => 'addoptions', "Управление свойствами таблицы" => 'optionstable', "Добавление свойства к таблице" => 'optionstableadd', "Редактирование свойства" => 'editoptions', "Удаление свойства" => 'deloptions', "Редактирование свойства у таблицы" => 'optionstableedit', "Удаления свойства у таблицы" => 'optionstabledel' );
	
	//Список таблица, свойства которых можно редактировать
	private $tables = array ("catalog" => "Каталог", "catalog_products" => "Товары каталога", "orders" => "Заказы", "Pages" => "Страницы", "News" => "Новости", "NewsCategorys" => "Категории новостей", "users_profiles" => "Профили пользователей");
	
	public function listoptions() {
		global $dbc;
		$center = '';
		$listing = '';
		if (isset ( $_GET ["page"] ) and ereg ( "([0-9])", $_GET ["page"] )) {
			$limit_ = $_GET ["page"];
		} else
			$limit_ = 1;
		$limit = $this->count * ($limit_ - 1);
		$dbc->query ( "select count(id) from data_types" );
		$res_count = $dbc->getResult ( 0, 0 );
		$pages = floor ( $res_count / $this->count );
		if (($res_count % $this->count) > 0)
			$pages ++;
		if ($pages > 1) {
			$listing = adminf::getListing ( $pages, $limit_, "#mod=ctrloptions&act=listoptions&page=" );
		}
		
		/**
		 * Тайтлы для таблицы
		 */
		$titles = array ("№", "Название", "Идентификатор", "Данные БД", "Данные формы", "Управление" );
		
		$names = array ();
		if (isset ( $_GET ["page"] ) && $_GET ["page"] == 'all') {
			$res1 = $dbc->query ( "select * from data_types order by id DESC", 1 );
		} else {
			$res1 = $dbc->query ( "select * from data_types order by id DESC limit " . $limit . "," . $this->count . "", 1 );
			$GLOBALS ["smarty"]->assign ( 'i_start', $limit );
		}
		
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$obj = mysql_fetch_assoc ( $res1 );
			
			$obj ["t_type_edit"] .= ($obj ["t_type_edit_alt"] != '') ? " (" . $obj ["t_type_edit_alt"] . ")" : "";
			
			$names [$i] = array ($obj ["t_name"], $obj ["t_source"], $obj ["t_type"], $obj ["t_type_edit"], '
				<a href="#mod=ctrloptions&act=editoptions&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=ctrloptions&act=listoptions&page={$limit_}" ) . '" title="Редактировать свойство">Изменить &raquo;</a><br />			
				<a href="#mod=ctrloptions&act=deloptions&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=ctrloptions&act=listoptions&page={$limit_}" ) . '" onclick="if (!question(\'Вы уверены, что хотите удалить свойство?\')) return false;" title="Удалить свойство">Удалить &raquo;</a>' );
		}
		$center .= adminf::set_tpl_list_1 ( $names, $titles, $listing );
		
		return $center;
	}
	
	public function addoptions() {
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
				$items [$i] [1] ["HELP"] = '';
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=ctrloptions&act=addoptions&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2') {
			//Проверяем, корректна ли ссылка страницы, и нет ли уже такой же
			if ($dbc->query ( "select count(id) from data_types where t_source='" . htmlspecialchars ( $_POST ["t_source"], ENT_QUOTES ) . "'", 6 ) != 0) {
				return 'Запись с таким Идентификатором уже существует!';
			} else {
				
				$sql1 = '';
				$sql2 = '';
				$i = 0;
				foreach ( $_POST as $name => $value ) {
					if ($i != 0) {
						$sql1 .= ",";
						$sql2 .= ",";
					}
					$sql1 .= "`{$name}`";
					$sql2 .= "'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $value ) ) ) . "'";
					$i ++;
				}
				$sql = "insert into data_types ({$sql1}) values ($sql2)";
				
				$dbc->query ( $sql );
				return '';
			}
		}
		
		return;
	}
	
	public function editoptions() {
		global $dbc, $smarty;
		
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from data_types where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=data_types&act=listoptions" );
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
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=ctrloptions&act=editoptions&uid={$obj["id"]}&step=2" );
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2') {
			//Проверяем, корректна ли ссылка страницы, и нет ли уже такой же
			if ($dbc->query ( "select count(id) from data_types where t_source='" . htmlspecialchars ( $_POST ["t_source"], ENT_QUOTES ) . "' and id!='{$obj["id"]}'", 6 ) != 0) {
				return 'Запись с таким Идентификатором уже существует!';
			} else {
				
				$sql1 = '';
				$sql2 = '';
				$i = 0;
				foreach ( $_POST as $name => $value ) {
					if ($i != 0) {
						$sql2 .= ",";
					}
					$sql2 .= "`{$name}`='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $value ) ) ) . "'";
					$i ++;
				}
				$sql = "update data_types set $sql2 where id='{$obj["id"]}'";
				
				$dbc->query ( $sql );
				return '';
			}
		}
		
		return;
	}
	
	public function optionstableedit() {
		global $dbc, $smarty;
		if (isset ( $_GET ["field"] ) and preg_match ( '/^_/', $_GET ["field"] )) {
			$obj = $dbc->query ( "SHOW FULL FIELDS FROM `{$_GET["table"]}` where Field = '{$_GET["field"]}'", 7 );
		} else {
			header ( "Location:?mod=ctrloptions&act=optionstable&table={$_GET["table"]}" );
		}
		
		if (! isset ( $_GET ["step"] )) {
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			$items_ = array ("name" => "input", "type" => "select", "comment" => "textsmall", "default" => "input" );
			$items_1 = array ("name" => "Field", "type" => "Type", "comment" => "Comment", "default" => "Default" );
			$items_names_ = array ("name" => "Название столбца", "type" => "Тип данных", "comment" => "Комментарий", "default" => "По-умолчанию" );
			$obj ["Field1"] = $obj ["Field"];
			foreach ( $items_ as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $items_names_ [$key];
				$items [$i] [1] ["NAME"] = $key;
				if ($key == 'name') {
					$t = explode ( "_", $obj [$items_1 [$key]] );
					unset ( $t [0] );
					unset ( $t [1] );
					$obj [$items_1 [$key]] = implode ( "_", $t );
				}
				$items [$i] [1] ["VALUE"] = str_replace ( '"', "&quot;", $obj [$items_1 [$key]] );
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$y = 0;
					$res1 = $dbc->query ( "select * from data_types", 1 );
					$t = explode ( "_", $obj ["Field1"] );
					for($y = 0; $y < mysql_num_rows ( $res1 ); $y ++) {
						$items [$i] [2] [$y] = array ("id" => mysql_result ( $res1, $y, "id" ), "name" => mysql_result ( $res1, $y, "t_name" ) );
						if (strtolower ( $t [1] ) == strtolower ( mysql_result ( $res1, $y, "t_source" ) )) {
							$items [$i] [2] [$y] ["selected"] = "1";
						}
					}
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=ctrloptions&act=optionstableedit&field={$_GET["field"]}&table={$_GET["table"]}&step=2" );
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2') {
			//Проверяем, корректна ли ссылка страницы, и нет ли уже такой же
			$_POST ["name2"] = strtolower ( $dbc->query ( "select t_source from data_types where id='{$_POST["type"]}'", 6 ) );
			$_POST ["type"] = $dbc->query ( "select t_type from data_types where id='{$_POST["type"]}'", 6 );
			$_POST ["name"] = "_{$_POST["name2"]}_{$_POST["name"]}";
			$dbc->query ( "ALTER TABLE  `{$_GET["table"]}` CHANGE  `{$_GET["field"]}`  `{$_POST["name"]}` {$_POST["type"]} NOT NULL DEFAULT '{$_POST["default"]}' COMMENT  '{$_POST["comment"]}'" );
			return '';
		}
		
		return;
	}
	
	public function optionstabledel() {
		global $dbc, $smarty;
		if (isset ( $_GET ["field"] ) and preg_match ( '/^_/', $_GET ["field"] )) {
			$obj = $dbc->query ( "SHOW FULL FIELDS FROM `{$_GET["table"]}` where Field = '{$_GET["field"]}'", 7 );
		} else {
			header ( "Location:?mod=ctrloptions&act=optionstable&table={$_GET["table"]}" );
		}
		$dbc->query ( "ALTER TABLE  `{$_GET["table"]}` DROP  `{$_GET["field"]}`" );
		header ( "Location:?" . base64_decode ( $_GET ["referer"] ) . "" );
		exit ();
	}
	
	public function optionstableadd() {
		global $dbc, $smarty;
		
		if (! isset ( $_GET ["step"] )) {
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			$items_ = array ("name" => "input", "type" => "select", "comment" => "textsmall", "default" => "input" );
			$items_names_ = array ("name" => "Название столбца", "type" => "Тип данных", "comment" => "Комментарий", "default" => "По-умолчанию" );
			foreach ( $items_ as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $items_names_ [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = '';
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$y = 0;
					$res1 = $dbc->query ( "select * from data_types", 1 );
					for($y = 0; $y < mysql_num_rows ( $res1 ); $y ++) {
						$items [$i] [2] [$y] = array ("id" => mysql_result ( $res1, $y, "id" ), "name" => mysql_result ( $res1, $y, "t_name" ) );
					}
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=ctrloptions&act=optionstableadd&table={$_GET["table"]}&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2') {
			//Проверяем, корректна ли ссылка страницы, и нет ли уже такой же
			$_POST ["name2"] = strtolower ( $dbc->query ( "select t_source from data_types where id='{$_POST["type"]}'", 6 ) );
			$_POST ["type"] = $dbc->query ( "select t_type from data_types where id='{$_POST["type"]}'", 6 );
			$_POST ["name"] = "_{$_POST["name2"]}_{$_POST["name"]}";
			$dbc->query ( "ALTER TABLE  `{$_GET["table"]}` ADD  `{$_POST["name"]}` {$_POST["type"]} NOT NULL DEFAULT  '{$_POST["default"]}' COMMENT  '{$_POST["comment"]}'" );
			return '';
		}
		
		return;
	}
	
	public function optionstable() {
		global $dbc;
		$center = '';
		
		if (isset ( $_GET ["table"] ) && isset ( $this->tables [$_GET ["table"]] )) {
			/**
			 * Список полей таблицы
			 */
			$res1 = $dbc->query ( "SHOW FULL FIELDS FROM `{$_GET ["table"]}`", 1 );
			
			/**
			 * Тайтлы для таблицы
			 */
			$titles = array ("№", "Название", "Тип", "Комментарий", "По-умолчанию", "Управление" );
			
			$names = array ();
			$GLOBALS ['smarty']->assign ( "alt", '<a href="#mod=ctrloptions&act=optionstableadd&table=' . $_GET ["table"] . '&referer=' . base64_encode ( "mod=ctrloptions&act=optionstable&table={$_GET ["table"]}" ) . '">Добавить новое поле &raquo;</a>' );
			while ( $row = mysql_fetch_assoc ( $res1 ) ) {
				$row ["Default"] = ($row ["Default"] == '') ? "&ndash;" : $row ["Default"];
				$row ["Comment"] = ($row ["Comment"] == '') ? "&ndash;" : $row ["Comment"];
				$alt = '<a href="#mod=ctrloptions&act=optionstableedit&table=' . $_GET ["table"] . '&field=' . $row ["Field"] . '&referer=' . base64_encode ( "mod=ctrloptions&act=optionstable&table={$_GET ["table"]}" ) . '" title="Изменить поле">Изменить &raquo;</a><br />';
				$alt .= '<a href="#mod=ctrloptions&act=optionstabledel&table=' . $_GET ["table"] . '&field=' . $row ["Field"] . '&referer=' . base64_encode ( "mod=ctrloptions&act=optionstable&table={$_GET ["table"]}" ) . '" title="Удалить поле">Удалить &raquo;</a>';
				if (! preg_match ( "/^_/", $row ["Field"] ))
					$alt = 'Данное поле системное';
				$names [] = array ($row ["Field"], $row ["Type"], $row ["Comment"], $row ["Default"], $alt );
			}
			
			$center .= adminf::set_tpl_list_1 ( $names, $titles, "" );
		
		} else {
			/**
			 * Тайтлы для таблицы
			 */
			$titles = array ("№", "Название", "Управление" );
			
			$names = array ();
			foreach ( $this->tables as $name => $value ) {
				$names [] = array ($value, '
			<a href="#mod=ctrloptions&act=optionstable&table=' . $name . '&referer=' . base64_encode ( "mod=ctrloptions&act=optionstable" ) . '" title="Перейти к управлению полями таблицы">Управлять &raquo;</a>' );
			}
			
			$center .= adminf::set_tpl_list_1 ( $names, $titles, "" );
		}
		return $center;
	}

}

$module = new ctrloptions ( );

$GLOBALS ['namemodule'] = $module->name;
?>