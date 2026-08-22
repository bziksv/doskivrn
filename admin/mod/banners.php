<?
class banners {
	
	private $count = 10;
	public $name = 'Баннеры';
	
	private $items = array ("p_desc" => "input", "p_status" => "radio", "p_comment" => "textsmall" );
	private $items_names = array ("p_desc" => "Название", "p_status" => "Публикация", "p_comment" => "Примечание" );
	
	private $items_banner = array ("b_type" => "select", "b_file_path" => "file", "b_text" => "text", "b_status" => "radio", "b_link" => "textsmall", "b_width" => "input", "b_height" => "input" );
	private $items_names_banner = array ("b_type" => "Тип", "b_file_path" => "Файл", "b_text" => "Текст", "b_status" => "Публикация", "b_link" => "Ссылка", "b_width" => "Ширина", "b_height" => "Высота" );
	
	public $methods = array ("Редактирование баннера" => "editbanner", "Удаление баннера" => "delbanner", "Список позиций" => "listbanners", "Удаление позиции" => 'delposition', "Добавление позиции" => 'addbannersposition', "Просмотр позиции" => 'viewposition', "Редактирование баннера" => 'editbanner', "Добавление баннера" => 'addbaner', "Редактирование позиции" => 'editposition' );
	
	public function listbanners() {
		global $dbc;
		
		$center = '';
		$listing = '';
		
		/**
		 * Тайтлы для таблицы
		 */
		$titles = array ("№", "Название", "Публикация", "Примечание", "Управление" );
		
		$names = array ();
		$res1 = $dbc->query ( "select * from banners_positions order by id ASC", 1 );
		$GLOBALS ['smarty']->assign ( "alt", '<a href="#mod=banners&act=addbannersposition">Добавить новую позицию&raquo;</a>' );
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$obj = mysql_fetch_assoc ( $res1 );
			
			switch ($obj ["p_status"]) {
				case 0 :
					$status = 'Скрыта';
					break;
				case 1 :
					$status = 'Публикуется';
					break;
				default :
					$status = 'н/д';
					break;
			}
			$obj ["p_comment"] = ($obj ["p_comment"] == "") ? "&nbsp;" : $obj ["p_comment"];
			$names [$i] = array ($obj ["p_desc"], $status, nl2br ( $obj ["p_comment"] ), '
			<a href="#mod=banners&act=editposition&uid=' . $obj ["id"] . '" title="Перейти к редактированию позиции">Изменить&raquo;</a><br />			
			<a href="#mod=banners&act=viewposition&uid=' . $obj ["id"] . '" title="Перейти к списку баннеров данной позиции">Баннеры&raquo;</a><br />			
			<a href="#mod=banners&act=delposition&position=' . $obj ["id"] . '" onclick="if (!question(\'Вы уверены, что хотите удалить данную позицию?\')) return false;" title="Удалить текущую позицию">Удалить&raquo;</a>' );
		}
		$center .= adminf::set_tpl_list_1 ( $names, $titles, $listing );
		
		return $center;
	}
	
	public function delposition() {
		global $dbc;
		if (isset ( $_GET ["position"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["position"] )) {
			$dbc->query ( "select count(id) from banners_positions where id='" . $_GET ["position"] . "'" );
			$obj = $dbc->getObject ();
			if (! $obj) {
				header ( "Location:?mod=banners&act=listbanners" );
				exit ();
			} else {
				//Удаляем саму позицию, удаляем все баннеры позиции
				$dbc->query ( "delete from banners_positions where id='" . $_GET ["position"] . "'" );
				$res1 = $dbc->query ( "select * from banners where p_id='" . $_GET ["position"] . "'", 1 );
				for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
					$obj = mysql_fetch_assoc ( $res1 );
					if ($obj ["b_file_path"] != '' && file_exists ( $_SERVER ['DOCUMENT_ROOT'] . $obj ["b_file_path"] )) {
						unlink ( $_SERVER ['DOCUMENT_ROOT'] . $obj ["b_file_path"] );
					}
					$dbc->query ( "delete from banners where id='{$obj["id"]}'" );
				}
			}
		}
		header ( "Location:?mod=banners&act=listbanners" );
		exit ();
	}
	
	public function editposition() {
		global $dbc, $smarty;
		
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from banners_positions where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=banners&act=listbanners" );
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
			$smarty->assign ( 'back', "mod=banners&act=listbanners" );
			
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'action', "?mod=banners&act=editposition&uid={$_GET["uid"]}&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2' and $_SERVER ['REQUEST_METHOD'] == 'POST') {
			if (trim ( $_POST ["p_desc"] ) == '')
				return 'Необходимо ввести название позиции';
			$_POST ["p_desc"] = htmlspecialchars ( $_POST ["p_desc"], ENT_QUOTES );
			$_POST ["p_status"] = intval ( $_POST ["p_status"] );
			$_POST ["p_comment"] = htmlspecialchars ( $_POST ["p_comment"], ENT_QUOTES );
			
			$dbc->query ( "update banners_positions set 
			p_desc='{$_POST ["p_desc"]}', 
			p_status='{$_POST ["p_status"]}', 
			p_comment='{$_POST ["p_comment"]}'
			where id='{$obj["id"]}'" );
			return 'success';
		}
		return false;
	}
	
	public function addbannersposition() {
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
				$smarty->assign ( 'back', urldecode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=banners&act=addbannersposition&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2' and $_SERVER ['REQUEST_METHOD'] == 'POST') {
			if (trim ( $_POST ["p_desc"] ) == '')
				return 'Необходимо ввести название позиции';
			$_POST ["p_desc"] = htmlspecialchars ( $_POST ["p_desc"], ENT_QUOTES );
			$_POST ["p_status"] = intval ( $_POST ["p_status"] );
			$_POST ["p_comment"] = htmlspecialchars ( $_POST ["p_comment"], ENT_QUOTES );
			
			$dbc->query ( "insert into banners_positions (p_desc, p_status, p_comment) values (
			'{$_POST ["p_desc"]}', '{$_POST ["p_status"]}', '{$_POST ["p_comment"]}')" );
			return 'success';
		}
		return false;
	}
	
	public function viewposition() {
		global $dbc;
		
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from banners_positions where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=banners&act=listbanners" );
			exit ();
		}
		if (! $obj) {
			header ( "Location:?mod=banners&act=listbanners" );
			exit ();
		}
		$center = '';
		$listing = '';
		$GLOBALS ['smarty']->assign ( "back", 'mod=banners&act=listbanners' );
		$GLOBALS ['smarty']->assign ( "alt", '<script type="text/javascript" src="/js/flash.js"></script>
		Позиция: <b>' . $obj ["p_desc"] . '</b><br/>
		Обращение в шаблоне к данной позиции: <b>{php}bannersctrl::init(' . $obj ["id"] . ');{/php}</b><br/><br/>
		<a href="#mod=banners&act=addbaner&position=' . $obj ["id"] . '">Добавить баннер на текущую позицию</a><br><br>' );
		
		/**
		 * Тайтлы для таблицы
		 */
		$titles = array ("№", "Тип", "Статус", "Содержание", "Ссылка", "Показов", "Управление" );
		
		$names = array ();
		$res1 = $dbc->query ( "select * from banners where p_id='{$obj["id"]}' order by id DESC", 1 );
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$obj = mysql_fetch_assoc ( $res1 );
			
			switch ($obj ["b_status"]) {
				case 0 :
					$status = 'Скрыта';
					break;
				case 1 :
					$status = 'Публикуется';
					break;
				default :
					$status = 'н/д';
					break;
			}
			switch ($obj ["b_type"]) {
				case 0 :
					$type = 'текст';
					$s = nl2br ( $obj ["b_text"] );
					break;
				case 1 :
					$type = 'HTML';
					$s = $obj ["b_text"];
					break;
				case 2 :
					$type = 'картинка';
					$s = '<a href="' . $obj ["b_file_path"] . '" target="_blank" title="Открыть в новом окне"><img src="' . $obj ["b_file_path"] . '" width="60" height="20"></a>';
					break;
				case 3 :
					$type = 'флеш';
					$s = '<a href="' . $obj ["b_file_path"] . '" target="_blank" title="Открыть в новом окне">Смотреть файл</a>';
					break;
			}
			$obj ["b_link"] = '<a href="' . $obj ["b_link"] . '" target="_blank" title="Открыть в новом окне">' . $obj ["b_link"] . '</a>';
			$names [$i] = array ($type, $status, $s, $obj ["b_link"], $obj ["b_stat_view"], '
			<a href="#mod=banners&act=editbanner&uid=' . $obj ["id"] . '" title="Перейти к редактированию позиции">Изменить&raquo;</a><br />			
			<a href="#mod=banners&act=delbanner&uid=' . $obj ["id"] . '" onclick="if (!question(\'Вы уверены, что хотите удалить данный баннер?\')) return false;" title="Удалить текущий баннер">Удалить&raquo;</a>' );
		}
		$center .= adminf::set_tpl_list_1 ( $names, $titles, $listing );
		
		return $center;
	}
	public function delbanner() {
		global $dbc;
		
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from banners where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=banners&act=listbanners" );
			exit ();
		}
		if (! $obj) {
			header ( "Location:?mod=banners&act=listbanners" );
			exit ();
		}
		
		#Удаляем файл
		if ($obj ["b_file_path"] != '' && file_exists ( $_SERVER ['DOCUMENT_ROOT'] . $obj ["b_file_path"] )) {
			unlink ( $_SERVER ['DOCUMENT_ROOT'] . $obj ["b_file_path"] );
		}
		#Удаялем запись в БД
		$dbc->query ( "delete from banners where id='{$obj["id"]}'" );
		header ( "Location:?mod=banners&act=viewposition&uid={$obj["p_id"]}" );
		exit ();
	}
	
	public function editbanner() {
		
		global $dbc, $smarty;
		
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from banners where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=banners&act=listbanners" );
			exit ();
		}
		if (! $obj) {
			header ( "Location:?mod=banners&act=listbanners" );
			exit ();
		}
		
		if (! isset ( $_GET ["step"] )) {
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			foreach ( $this->items_banner as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $this->items_names_banner [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = str_replace ( '"', "&quot;", $obj [$key] );
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$items [$i] [2] [] = array ("id" => 2, "name" => "Картинка" );
					$items [$i] [2] [] = array ("id" => 0, "name" => "Текст" );
					$items [$i] [2] [] = array ("id" => 1, "name" => "HTML" );
					$items [$i] [2] [] = array ("id" => 3, "name" => "Flash" );
					foreach ( $items [$i] [2] as $key2 => $value ) {
						if ($obj [$key] == $items [$i] [2] [$key2] ["id"]) {
							$items [$i] [2] [$key2] ["selected"] = "1";
						}
					}
				}
			}
			$smarty->assign ( 'back', "mod=banners&act=viewposition&uid={$obj["p_id"]}" );
			$smarty->assign ( 'action', "?mod=banners&act=editbanner&uid={$obj["id"]}&step=2" );
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2' and $_SERVER ['REQUEST_METHOD'] == 'POST') {
			if ($_POST ["b_type"] != '0' && $_POST ["b_type"] != '1' && $_POST ["b_link"] == '')
				return 'Необходимо ввести ссылку баннера';
			
			$file = $obj ["b_file_path"];
			if (isset ( $_FILES ["b_file_path"] ) && $_FILES ["b_file_path"] ["error"] == 0 && $_FILES ["b_file_path"] ["size"] > 0) {
				$info = getimagesize ( $_FILES ["b_file_path"] ["tmp_name"] );
				switch ($info ['mime']) {
					case "image/gif" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".gif";
						break;
					case "image/jpeg" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".jpg";
						break;
					case "image/png" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".png";
						break;
					case "application/x-shockwave-flash" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".swf";
						break;
				}
				if (! isset ( $fileName )) {
					return "Некорретный формат картинки: {$info ['mime']}";
				} else {
					@copy ( $_FILES ["b_file_path"] ["tmp_name"], $GLOBALS ['path_banners'] . $fileName );
					$file = $GLOBALS ['path_banners_view'] . $fileName;
					if ($obj ["b_file_path"] != '' && file_exists ( $_SERVER ['DOCUMENT_ROOT'] . $obj ["b_file_path"] )) {
						unlink ( $_SERVER ['DOCUMENT_ROOT'] . $obj ["b_file_path"] );
					}
				}
			} elseif ($file == '' && $_POST ["b_type"] != '0' && $_POST ["b_type"] != '1') {
				return 'Невозможно закачать файл';
			}
			$_POST ["b_width"] = intval ( $_POST ["b_width"] );
			$_POST ["b_height"] = intval ( $_POST ["b_height"] );
			$_POST ["b_type"] = intval ( $_POST ["b_type"] );
			$_POST ["b_status"] = intval ( $_POST ["b_status"] );
			$_POST ["b_link"] = str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["b_link"] ) ) );
			$_POST ["b_text"] = str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["b_text"] ) ) );
			$dbc->query ( "UPDATE `banners` set
			`b_type`='{$_POST["b_type"]}', 
			`b_file_path`='{$file}', 
			`b_status`='{$_POST["b_status"]}', 
			`b_link`='{$_POST ["b_link"]}', 
			`b_text`='{$_POST ["b_text"]}', 
			`b_width`='{$_POST ["b_width"]}', 
			`b_height`='{$_POST ["b_height"]}'
			where id='{$obj["id"]}'" );
			
			return 'success';
		}
		return false;
	}
	
	public function addbaner() {
		
		global $dbc, $smarty;
		
		if (isset ( $_GET ["position"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["position"] )) {
			$obj = $dbc->query ( "select * from banners_positions where id='" . $_GET ["position"] . "'", 7 );
		} else {
			header ( "Location:?mod=banners&act=listbanners" );
			exit ();
		}
		if (! $obj) {
			header ( "Location:?mod=banners&act=listbanners" );
			exit ();
		}
		
		if (! isset ( $_GET ["step"] )) {
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			foreach ( $this->items_banner as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $this->items_names_banner [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = '';
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$items [$i] [2] [] = array ("id" => 2, "name" => "Картинка" );
					$items [$i] [2] [] = array ("id" => 0, "name" => "Текст" );
					$items [$i] [2] [] = array ("id" => 1, "name" => "HTML" );
					$items [$i] [2] [] = array ("id" => 3, "name" => "Flash" );
				}
			}
			$smarty->assign ( 'back', "mod=banners&act=viewposition&uid={$obj["id"]}" );
			$smarty->assign ( 'action', "?mod=banners&act=addbaner&position={$obj["id"]}&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2' and $_SERVER ['REQUEST_METHOD'] == 'POST') {
			if ($_POST ["b_type"] != '0' && $_POST ["b_type"] != '1' && $_POST ["b_link"] == '')
				return 'Необходимо ввести ссылку баннера';
			
			$file = '';
			if (isset ( $_FILES ["b_file_path"] ) && $_FILES ["b_file_path"] ["error"] == 0 && $_FILES ["b_file_path"] ["size"] > 0) {
				$info = getimagesize ( $_FILES ["b_file_path"] ["tmp_name"] );
				switch ($info ['mime']) {
					case "image/gif" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".gif";
						break;
					case "image/jpeg" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".jpg";
						break;
					case "image/png" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".png";
						break;
					case "application/x-shockwave-flash" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".swf";
						break;
				}
				if (! isset ( $fileName )) {
					return "Некорретный формат картинки: {$info ['mime']}";
				} else {
					@copy ( $_FILES ["b_file_path"] ["tmp_name"], $GLOBALS ['path_banners'] . $fileName );
					$file = $GLOBALS ['path_banners_view'] . $fileName;
				}
			} elseif ($_POST ["b_type"] != '0' && $_POST ["b_type"] != '1') {
				return 'Невозможно закачать файл';
			}
			$_POST ["b_width"] = intval ( $_POST ["b_width"] );
			$_POST ["b_height"] = intval ( $_POST ["b_height"] );
			$_POST ["b_type"] = intval ( $_POST ["b_type"] );
			$_POST ["b_status"] = intval ( $_POST ["b_status"] );
			$_POST ["b_link"] = str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["b_link"] ) ) );
			$_POST ["b_text"] = str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["b_text"] ) ) );
			$dbc->query ( "INSERT INTO `banners` 
			(`p_id`, `b_type`, `b_file_path`, `b_status`, `b_link`, `b_width`, `b_height`, `b_text`) 
			VALUES ('{$obj["id"]}', '{$_POST["b_type"]}', '{$file}', '{$_POST["b_status"]}', '{$_POST ["b_link"]}', '{$_POST ["b_width"]}', '{$_POST ["b_height"]}', '{$_POST ["b_link"]}')" );
			
			return 'success';
		}
		return false;
	}
}

$module = new banners ( );

$GLOBALS ['namemodule'] = $module->name;
?>