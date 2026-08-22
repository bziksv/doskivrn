<?
//Статические страницы
//+


class pages {
	
	private $count = 25;
	public $name = 'Страницы';
	private $items = array ("name" => "input", "link" => "input", "pageContent" => "editor", "pageTitle" => "textsmall", "pageDescription" => "textsmall", "pageKeywords" => "textsmall" );
	private $items_names = array ("name" => "Название страницы", "link" => "Идентификатор", "pageContent" => "Содержимое страницы", "pageTitle" => "META TITLE", "pageDescription" => "META DESCRIPTION", "pageKeywords" => "META KEYWORDS" );
	private $pattern = "/^[a-zA-Z0-9_]{3,50}$/";
	public $methods = array ("Список страниц" => 'listpages', "Добавление страницы" => 'addpage', "Удаление страницы" => 'delpage', "Редактирование страницы" => 'editpage' );
	
	public function __construct() {
		global $dbc;
		/**
		 * Вынимаем дополнительные поля
		 */
		$res1 = $dbc->query ( "SHOW FULL FIELDS FROM `Pages`", 1 );
		while ( $row = mysql_fetch_assoc ( $res1 ) ) {
			if (preg_match ( "/^_/", $row ["Field"] )) {
				$t = explode("_",$row ["Field"]);
				$this->items[$row ["Field"]] = $dbc->query("select t_type_edit from data_types where t_source='{$t[1]}'",6); 
				$this->items_names[$row ["Field"]] = $row ["Comment"]; 
			}
		}
	}
	
	public function listpages() {
		global $dbc;
		$center = '';
		$listing = '';
		if (isset ( $_GET ["page"] ) and ereg ( "([0-9])", $_GET ["page"] )) {
			$limit_ = $_GET ["page"];
		} else
			$limit_ = 1;
			
		/**
		 * *******************ПОИСК*******************
		 */
		$items_names = array ();
		foreach ( $this->items_names as $key => $value ) {
			if (isset ( $this->items [$key] ) && $this->items [$key] == 'input') {
				$items_names [$key] = $value;
			}
		}
		$GLOBALS ["smarty"]->assign ( 'items_names', $items_names );
		$GLOBALS ["smarty"]->assign ( 'action_search', "#mod=pages&act=listpages" );
		$search = '';
		if (isset ( $_GET ["search"] ) && $_GET ["search"] == 'true' && trim ( $_GET ["word"] ) != '' and isset ( $this->items_names [$_GET ["field"]] )) {
			$_GET ["word"] = htmlspecialchars ( $_GET ["word"], ENT_QUOTES );
			$search = " WHERE `{$_GET ["field"]}` like ('{$_GET ["word"]}%')";
		}
		/**
		 * ********************************
		 */	
		
		$limit = $this->count * ($limit_ - 1);
		$dbc->query ( "select count(id) from Pages{$search}" );
		$res_count = $dbc->getResult ( 0, 0 );
		$pages = floor ( $res_count / $this->count );
		if (($res_count % $this->count) > 0)
			$pages ++;
		if ($pages > 1) {
			$listing = adminf::getListing ( $pages, $limit_, "#mod=pages&act=listpages&page=" );
		}
		
		/**
		 * Тайтлы для таблицы
		 */
		$titles = array ("№", "Название", "Создана", "Обновлена", "Ссылка", "Управление" );
		
		$names = array ();
		if (isset ( $_GET ["page"] ) && $_GET ["page"] == 'all') {
			$res1 = $dbc->query ( "select * from Pages{$search} order by id DESC", 1 );
		} else {
			$res1 = $dbc->query ( "select * from Pages{$search} order by id DESC limit " . $limit . "," . $this->count . "", 1 );
			$GLOBALS ["smarty"]->assign ( 'i_start', $limit );
		}
		
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$obj = mysql_fetch_assoc ( $res1 );
			
			$names [$i] = array ($obj ["name"], adminf::set_date ( $obj ["dataAdd"] ), adminf::set_date ( $obj ["dataUpdate"] ), "<a href=\"http://{$_SERVER ['HTTP_HOST']}/pages/{$obj["link"]}/\" target=\"_blank\">http://{$_SERVER ['HTTP_HOST']}/pages/{$obj["link"]}/</a>", '<a href="#mod=pages&act=editpage&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=pages&act=listpages&page={$limit_}" ) . '" title="Перейти к управлению страницей">Перейти &raquo;</a><br />			
				<a href="#mod=pages&act=delpage&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=pages&act=listpages&page={$limit_}" ) . '" onclick="if (!question(\'Вы уверены, что хотите удалить данную страницу?\')) return false;" title="Удалить текущую страницу">Удалить &raquo;</a>' );
		}
		$center .= adminf::set_tpl_list_1 ( $names, $titles, $listing );
		
		return $center;
	}
	
	public function addpage() {
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
			$smarty->assign ( 'action', "?mod=pages&act=addpage&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2') {
			if (preg_match ( $this->pattern, $_POST ["link"] )) {
				//Проверяем, корректна ли ссылка страницы, и нет ли уже такой же
				if ($dbc->query ( "select count(id) from Pages where link='" . htmlspecialchars ( $_POST ["link"], ENT_QUOTES ) . "'", 6 ) != 0) {
					return 'Страница с такой ссылкой уже существует!';
				} else {
					$dbc->query ( "insert into Pages (
					name,
					link,
					system,
					adminAdd,
					dataAdd,
					pageTitle,
					pageDescription,
					pageKeywords,
					_string_ico,
					pageContent
					) values (
	    			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["name"] ) ) ) . "',
	    			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["link"] ) ) ) . "',
	    			'0',
	    			'" . $dbc->query ( "select id from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"], ENT_QUOTES ) . "'", 6 ) . "',
	    			'" . time () . "',
	    			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["pageTitle"] ) ) ) . "',
	    			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["pageDescription"] ) ) ) . "',
	    			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["pageKeywords"] ) ) ) . "',
	    			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["_string_ico"] ) ) ) . "',
	    			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["pageContent"] ) ) ) . "'
	    			)" );
					return '';
				}
			} else
				return 'Некорректно введена ссылка страницы';
		}
		
		return;
	}
	
	public function delpage() {
		global $dbc;
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$dbc->query ( "delete from Pages where id='" . $_GET ["uid"] . "'" );
			//$center='Страница успешно удалена';
			if (isset ( $_GET ["referer"] )) {
				$_GET ["referer"] = base64_decode ( $_GET ["referer"] );
				header ( "Location:?{$_GET ["referer"]}" );
			} else {
				header ( "Location:?mod=pages&act=listpages" );
			}
		} else
			$center = 'Страница не найдена!';
		return $center .= '<br><br><a href="#mod=pages&act=listpages">Назад</a>';
	}
	
	public function editpage() {
		global $dbc, $smarty;
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from Pages where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=pages&act=listpages" );
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
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'action', "?mod=pages&act=editpage&uid={$obj["id"]}&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2' and $_SERVER ['REQUEST_METHOD'] == 'POST') {
			if (preg_match ( $this->pattern, $_POST ["link"] )) {
				$dbc->query ( "update Pages set 
					name='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["name"] ) ) ) . "',
					link='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["link"] ) ) ) . "',
					adminUpdate='" . $dbc->query ( "select id from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"], ENT_QUOTES ) . "'", 6 ) . "',
					dataUpdate='" . time () . "',
					pageTitle='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["pageTitle"] ) ) ) . "',
					pageDescription='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["pageDescription"] ) ) ) . "',
					pageKeywords='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["pageKeywords"] ) ) ) . "',
					_string_ico='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["_string_ico"] ) ) ) . "',
					pageContent='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["pageContent"] ) ) ) . "'
					where id='{$obj["id"]}'" );
				return '';
			} else
				return 'Некорректно введена ссылка страницы';
		}
		return $center;
	}
}

$module = new pages ( );

$GLOBALS ['namemodule'] = $module->name;
?>