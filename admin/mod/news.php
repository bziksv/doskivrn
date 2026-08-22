<?

//+
class news {
	
	private $count = 10;
	public $name = 'Новости сайта';
	
	private $small_foto = 214;
	private $small_foto_ = 'w'; //По какой обрезаем: w или h, ширина или высота
	private $full_foto = 800;
	private $full_foto_ = 'w'; //По какой обрезаем 
	//Папка для картинок
	private $fotos_folder = "/img/news/";
	
	private $items = array ("newsTitle" => "input", "file" => "file", "dataNews1" => "select", "dataNews2" => "select", "dataNews3" => "select", "newsStatus" => "radio", "newsCat" => "select", "newsPreview" => "editor", "newsContent" => "editor", "newsPageTitle" => "textsmall", "newsPageDesc" => "textsmall", "newsPageKeywords" => "textsmall" );
	private $items2 = array ("catName" => "input", "catStatus" => "radio", "catParent" => "select" );
	
	private $items_names = array ("newsTitle" => "Заголовок новости", "file" => "Картинка новости", "dataNews1" => "Дата новости:<br />число", "dataNews2" => "месяц", "dataNews3" => "год", "newsStatus" => "Публикация", "newsCat" => "Раздел", "newsPreview" => "Краткое описание", "newsContent" => "Полный текст новости", "newsPageTitle" => "META TITLE", "newsPageDesc" => "META DESCRIPTION", "newsPageKeywords" => "META KEYWORDS" );
	private $items_names2 = array ("catName" => "Название раздела", "catStatus" => "Публикация", "catParent" => "Раздел-родитель" );
	
	public $methods = array ("Список новостей" => 'listpages', "Добавление новости" => 'addnew', "Удаление новости" => 'del', "Редактирование новости" => 'edit', "Список категорий" => 'cats', "Добавление новой категории" => 'addnewcat', "Редактирование категории" => 'editcat', "Удаление категории" => 'delcat' );
	
	public function __construct() {
		global $dbc;
		/**
		 * Вынимаем дополнительные поля
		 */
		$res1 = $dbc->query ( "SHOW FULL FIELDS FROM `News`", 1 );
		while ( $row = mysql_fetch_assoc ( $res1 ) ) {
			if (preg_match ( "/^_/", $row ["Field"] )) {
				$t = explode ( "_", $row ["Field"] );
				$this->items [$row ["Field"]] = $dbc->query ( "select t_type_edit from data_types where t_source='{$t[1]}'", 6 );
				$this->items_names [$row ["Field"]] = $row ["Comment"];
			}
		}
		
		/**
		 * Вынимаем дополнительные поля
		 */
		$res1 = $dbc->query ( "SHOW FULL FIELDS FROM `NewsCategorys`", 1 );
		while ( $row = mysql_fetch_assoc ( $res1 ) ) {
			if (preg_match ( "/^_/", $row ["Field"] )) {
				$t = explode ( "_", $row ["Field"] );
				$this->items2 [$row ["Field"]] = $dbc->query ( "select t_type_edit from data_types where t_source='{$t[1]}'", 6 );
				$this->items_names2 [$row ["Field"]] = $row ["Comment"];
			}
		}
	}
	
	private function f_ch($m) {
		switch ($m) {
			case "01" :
				$m = 'Января';
				break;
			case "02" :
				$m = 'Февраля';
				break;
			case "03" :
				$m = 'Марта';
				break;
			case "04" :
				$m = 'Апреля';
				break;
			case "05" :
				$m = 'Мая';
				break;
			case "06" :
				$m = 'Июня';
				break;
			case "07" :
				$m = 'Июля';
				break;
			case "08" :
				$m = 'Августа';
				break;
			case "09" :
				$m = 'Сентября';
				break;
			case "10" :
				$m = 'Октября';
				break;
			case "11" :
				$m = 'Ноября';
				break;
			case "12" :
				$m = 'Декабря';
				break;
		}
		return $m;
	}
	private function f_ch_() {
		$m = array ();
		$m ["01"] = 'Января';
		$m ["02"] = 'Февраля';
		$m ["03"] = 'Марта';
		$m ["04"] = 'Апреля';
		$m ["05"] = 'Мая';
		$m ["06"] = 'Июня';
		$m ["07"] = 'Июля';
		$m ["08"] = 'Августа';
		$m ["09"] = 'Сентября';
		$m ["10"] = 'Октября';
		$m ["11"] = 'Ноября';
		$m ["12"] = 'Декабря';
		return $m;
	}
	private function f_ch_l($m) {
		switch ($m) {
			case "01" :
				$m = 'January';
				break;
			case "02" :
				$m = 'February';
				break;
			case "03" :
				$m = 'March';
				break;
			case "04" :
				$m = 'April';
				break;
			case "05" :
				$m = 'May';
				break;
			case "06" :
				$m = 'June';
				break;
			case "07" :
				$m = 'July';
				break;
			case "08" :
				$m = 'August';
				break;
			case "09" :
				$m = 'September';
				break;
			case "10" :
				$m = 'October';
				break;
			case "11" :
				$m = 'November';
				break;
			case "12" :
				$m = 'December';
				break;
		}
		return $m;
	}
	
	public function listpages() {
		global $dbc;
		
		$center = '';
		$listing = '';
		if (isset ( $_GET ["page"] ) and ereg ( "([0-9])", $_GET ["page"] )) {
			$limit_ = $_GET ["page"];
		} else
			$limit_ = 1;
		$limit = $this->count * ($limit_ - 1);
		$dbc->query ( "select count(id) from News" );
		$res_count = $dbc->getResult ( 0, 0 );
		$pages = floor ( $res_count / $this->count );
		if (($res_count % $this->count) > 0)
			$pages ++;
		if ($pages > 1) {
			$listing = adminf::getListing ( $pages, $limit_, "#mod=news&act=listpages&page=" );
		}
		
		/**
		 * Тайтлы для таблицы
		 */
		$titles = array ("№", "Заголовок", "Дата", "Статус", "Ссылка", "Управление" );
		
		$names = array ();
		if (isset ( $_GET ["page"] ) && $_GET ["page"] == 'all') {
			$res1 = $dbc->query ( "select * from News order by dataNews DESC", 1 );
		} else {
			$res1 = $dbc->query ( "select * from News order by dataNews DESC limit " . $limit . "," . $this->count . "", 1 );
			$GLOBALS ["smarty"]->assign ( 'i_start', $limit );
		}
		
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$obj = mysql_fetch_assoc ( $res1 );
			
			switch ($obj ["newsStatus"]) {
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
			
			$names [$i] = array ($obj ["newsTitle"], date ( "d.m.Y", $obj ["dataNews"] ), $status, "<a href=\"http://{$_SERVER ['HTTP_HOST']}/news/view/{$obj["id"]}/\" target=\"_blank\">http://{$_SERVER ['HTTP_HOST']}/news/view/{$obj["id"]}/</a>", '
			<a href="#&mod=news&act=edit&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=news&act=listpages&page={$limit_}" ) . '" title="Перейти к управлению статьей">Перейти&raquo;</a><br />						
			<a href="#mod=news&act=del&uid=' . $obj ["id"] . '&referer=' . base64_encode ( "mod=news&act=listpages&page={$limit_}" ) . '" onclick="if (!question(\'Вы уверены, что хотите удалить данную страницу?\')) return false;" title="Удалить текущую страницу">Удалить&raquo;</a>' );
		}
		$center .= adminf::set_tpl_list_1 ( $names, $titles, $listing );
		
		return $center;
	}
	
	public function main() {
		return $this->listpages ();
	}
	private function get_structure_select($array = false, $padding = '') {
		$GLOBALS ["_select"] [] = array ($array [0] [1], $padding . $array [0] [0], $array [2] );
		if (isset ( $array [1] ) && count ( $array [1] ) > 0) {
			foreach ( $array [1] as $name => $value )
				$this->get_structure_select ( $value, $padding . '&nbsp;&nbsp;' );
		}
	}
	public function addnew() {
		global $dbc, $smarty;
		$center = '';
		if (! isset ( $_GET ["step"] )) {
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			$arrayCat = $this->getStructureCat ( 0 );
			foreach ( $arrayCat as $name => $value )
				$this->get_structure_select ( $value, '' );
			$year = date ( "Y" );
			$mon = date ( "m" );
			$day = date ( "d" );
			foreach ( $this->items as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $this->items_names [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = '';
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [1] ["NAME"] == 'newsCat') {
					//Категории
					$items [$i] [2] = array ();
					$items [$i] [2] [0] = array ("id" => 0, "name" => "--без раздела--" );
					$y = 1;
					foreach ( $GLOBALS ["_select"] as $value ) {
						$items [$i] [2] [$y] = array ("id" => $value [0], "name" => $value [1] );
						$y ++;
					}
				}
				if ($items [$i] [1] ["NAME"] == 'dataNews1') {
					//Категории
					$items [$i] [2] = array ();
					for($y = 1; $y < 32; $y ++) {
						$items [$i] [2] [$y - 1] = array ("id" => $y, "name" => $y );
						if (intval ( $day ) == intval ( $items [$i] [2] [$y - 1] ["id"] )) {
							$items [$i] [2] [$y - 1] ["selected"] = "1";
						}
					}
				}
				if ($items [$i] [1] ["NAME"] == 'dataNews2') {
					//Категории
					$items [$i] [2] = array ();
					foreach ( $this->f_ch_ () as $key => $value ) {
						$items [$i] [2] [$y] = array ("id" => $key, "name" => $value );
						if ($mon == $items [$i] [2] [$y] ["id"]) {
							$items [$i] [2] [$y] ["selected"] = "1";
						}
						$y ++;
					}
				}
				if ($items [$i] [1] ["NAME"] == 'dataNews3') {
					$items [$i] [2] = array ();
					$ye = intval ( date ( "Y" ) - 5 );
					$year = intval ( $year );
					for($y = $ye; $y <= $year + 1; $y ++) {
						$items [$i] [2] [$y] = array ("id" => $y, "name" => $y );
						if (intval ( $year ) == intval ( $items [$i] [2] [$y] ["id"] )) {
							$items [$i] [2] [$y] ["selected"] = "1";
						}
					}
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=news&act=addnew&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2' and $_SERVER ['REQUEST_METHOD'] == 'POST') {
			
			if (trim ( $_POST ["newsTitle"] ) == '')
				return 'Необходимо ввести название новости';
			if (trim ( $_POST ["newsContent"] ) == '')
				return 'Необходимо ввести текст новости';
			
			$_POST ["file"] = '';
			if (isset ( $_FILES ["file"] ) && $_FILES ["file"] ["error"] == 0 && $_FILES ["file"] ["size"] > 0) {
				//смотрим формат файла
				switch ($_FILES ["file"] ["type"]) {
					case "image/gif" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".gif";
						break;
					case "image/jpeg" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".jpg";
						break;
					case "image/png" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".png";
						break;
				}
				if (! isset ( $fileName )) {
					return "Некорретный формат картинки";
				} else {
					@copy ( $_FILES ["file"] ["tmp_name"], $_SERVER ['DOCUMENT_ROOT'] . "{$this->fotos_folder}s_{$fileName}" );
					$this->createImage ( $_SERVER ['DOCUMENT_ROOT'] . "{$this->fotos_folder}s_{$fileName}", "small" );
					$_POST ["file"] = "s_" . $fileName;
					//$file = $fileName;
				}
			}
			
			$dataNews = $_POST ["dataNews1"] . ' ' . $this->f_ch_l ( $_POST ["dataNews2"] ) . ' ' . $_POST ["dataNews3"];
			$_POST ["dataNews"] = strtotime ( $dataNews );
			$_POST ["userAdd"] = $dbc->query ( "select id from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"], ENT_QUOTES ) . "'", 6 );
			$_POST ["dataAddNews"] = time ();
			unset ( $_POST ["dataNews1"] );
			unset ( $_POST ["dataNews2"] );
			unset ( $_POST ["dataNews3"] );
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
			$sql = "insert into News ({$sql1}) values ($sql2)";
			$dbc->query ( $sql );
			return 'success';
		}
	}
private function createImage($uploadedfile, $type = 'small') {
		if (! $info = getimagesize ( $uploadedfile ))
			return false;
		switch ($info ['mime']) {
			case 'image/jpeg' :
				$src = imagecreatefromjpeg ( $uploadedfile );
				break;
			case 'image/gif' :
				$src = imagecreatefromgif ( $uploadedfile );
				break;
			case 'image/png' :
				$src = imagecreatefrompng ( $uploadedfile );
				
				break;
			default :
				return false;
		}
		
		$size = getimagesize ( $uploadedfile );
		/**
		 * Если маленькая картинка
		 */
		if ($type == 'small') {
			if ($this->small_foto_ == 'w' && $size [0] > $this->small_foto) {
				$newWidth = $this->small_foto;
				$per = $this->small_foto / $size [0];
				$newHeight = $size [1] * $per;
			} elseif ($this->small_foto_ == 'h' && $size [1] > $this->small_foto) {
				$newHeight = $this->small_foto;
				$per = $this->small_foto / $size [1];
				$newWidth = $size [0] * $per;
			} else {
				$newHeight = $size [1];
				$newWidth = $size [0];
			}
		} elseif ($type == 'full') {
			if ($this->full_foto_ == 'w' && $size [0] > $this->full_foto) {
				$newWidth = $this->full_foto;
				$per = $this->full_foto / $size [0];
				$newHeight = $size [1] * $per;
			} elseif ($this->full_foto_ == 'h' && $size [1] > $this->full_foto) {
				$newHeight = $this->full_foto;
				$per = $this->full_foto / $size [1];
				$newWidth = $size [0] * $per;
			} else {
				$newHeight = $size [1];
				$newWidth = $size [0];
			}
		}
		
		$tmp = imagecreatetruecolor ( $newWidth, $newHeight );
		
		imagealphablending($tmp, false);
		imagesavealpha($tmp,true);
		$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
		imagefilledrectangle($tmp, 0, 0, $newWidth, $newHeight, $transparent);
		
		imagecopyresampled ( $tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $info [0], $info [1] );
		switch ($info ['mime']) {
			case 'image/jpeg' :
				imagejpeg ( $tmp, $uploadedfile, 100 );
				break;
			case 'image/gif' :
				imagegif ( $tmp, $uploadedfile, 100 );
				break;
			case 'image/png' :
				
				
				imagepng ( $tmp, $uploadedfile );
				break;
		}
		imagedestroy ( $src );
		imagedestroy ( $tmp );
		return true;
	}
	public function del() {
		global $dbc;
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$dbc->query ( "delete from News where id='" . $_GET ["uid"] . "'" );
			$center = 'Новость успешно удалена';
			header ( "Location:?mod=news&act=listpages" );
		} else
			$center = 'Новость не найдена!';
		return $center .= '<br><br><a href="?mod=news&act=listpages">Назад</a>';
	}
	
	public function edit() {
		global $dbc, $smarty;
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from News where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=articles&act=listpages" );
		}
		
		if (! isset ( $_GET ["step"] )) {
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			$arrayCat = $this->getStructureCat ( 0 );
			foreach ( $arrayCat as $name => $value )
				$this->get_structure_select ( $value, '' );
			$year = date ( "Y", $obj ["dataNews"] );
			$mon = date ( "m", $obj ["dataNews"] );
			$day = date ( "d", $obj ["dataNews"] );
			foreach ( $this->items as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $this->items_names [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = (isset ( $obj [$key] )) ? $obj [$key] : "";
				if ($key == 'file' && $items [$i] [1] ["VALUE"] != ''){
					$items [$i] [1] ["VALUE"] = $this->fotos_folder.''.$items [$i] [1] ["VALUE"];
				}
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [1] ["NAME"] == 'newsCat') {
					//Категории
					$items [$i] [2] = array ();
					$items [$i] [2] [0] = array ("id" => 0, "name" => "--без раздела--" );
					$y = 1;
					foreach ( $GLOBALS ["_select"] as $value ) {
						$items [$i] [2] [$y] = array ("id" => $value [0], "name" => $value [1] );
						if ($obj [$key] == $items [$i] [2] [$y] ["id"]) {
							$items [$i] [2] [$y] ["selected"] = "1";
						}
						$y ++;
					}
				}
				if ($items [$i] [1] ["NAME"] == 'dataNews1') {
					
					//Категории
					$items [$i] [2] = array ();
					for($y = 1; $y < 32; $y ++) {
						$items [$i] [2] [$y - 1] = array ("id" => $y, "name" => $y );
						if (intval ( $day ) == intval ( $items [$i] [2] [$y - 1] ["id"] )) {
							$items [$i] [2] [$y - 1] ["selected"] = "1";
						}
					}
				}
				if ($items [$i] [1] ["NAME"] == 'dataNews2') {
					//Категории
					$items [$i] [2] = array ();
					foreach ( $this->f_ch_ () as $key => $value ) {
						$items [$i] [2] [$y] = array ("id" => $key, "name" => $value );
						if ($mon == $items [$i] [2] [$y] ["id"]) {
							$items [$i] [2] [$y] ["selected"] = "1";
						}
						$y ++;
					}
				}
				if ($items [$i] [1] ["NAME"] == 'dataNews3') {
					$items [$i] [2] = array ();
					$ye = intval ( date ( "Y" ) - 5 );
					$year = intval ( $year );
					for($y = $ye; $y <= $year + 1; $y ++) {
						$items [$i] [2] [$y] = array ("id" => $y, "name" => $y );
						if (intval ( $year ) == intval ( $items [$i] [2] [$y] ["id"] )) {
							$items [$i] [2] [$y] ["selected"] = "1";
						}
					}
				}
			}
			if (isset ( $_GET ["referer"] )) {
				$smarty->assign ( 'back', base64_decode ( $_GET ["referer"] ) );
			}
			$smarty->assign ( 'action', "?mod=news&act=edit&uid={$obj["id"]}&step=2" );
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		} elseif ($_GET ["step"] == '2' and $_SERVER ['REQUEST_METHOD'] == 'POST') {
			if (trim ( $_POST ["newsTitle"] ) == '')
				return 'Необходимо ввести название новости';
			if (trim ( $_POST ["newsContent"] ) == '')
				return 'Необходимо ввести текст новости';
			
			if (isset ( $_FILES ["file"] ) && $_FILES ["file"] ["error"] == 0 && $_FILES ["file"] ["size"] > 0) {
				//смотрим формат файла
				switch ($_FILES ["file"] ["type"]) {
					case "image/gif" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".gif";
						break;
					case "image/jpeg" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".jpg";
						break;
					case "image/png" :
						$fileName = md5 ( md5 ( microtime () ) ) . ".png";
						break;
				}
				if (! isset ( $fileName )) {
					return "Некорретный формат картинки";
				} else {
					@copy ( $_FILES ["file"] ["tmp_name"], $_SERVER ['DOCUMENT_ROOT'] . "{$this->fotos_folder}s_{$fileName}" );
					$this->createImage ( $_SERVER ['DOCUMENT_ROOT'] . "{$this->fotos_folder}s_{$fileName}", "small" );
					$_POST ["file"] = "s_" . $fileName;
					if ($obj ["file"] != '' && file_exists ( $_SERVER ['DOCUMENT_ROOT'] . "{$this->fotos_folder}{$obj ["file"]}" ))
						unlink ( $_SERVER ['DOCUMENT_ROOT'] . "{$this->fotos_folder}{$obj ["file"]}" );
				}
			} else {
				if (isset($_POST ["file_del"])){
					if ($obj ["file"] != '' && file_exists ( $_SERVER ['DOCUMENT_ROOT'] . "{$this->fotos_folder}{$obj ["file"]}" )){
						unlink ( $_SERVER ['DOCUMENT_ROOT'] . "{$this->fotos_folder}{$obj ["file"]}" );
					}
					$_POST ["file"] = '';
				} else {
					$_POST ["file"] = $obj ["file"];
				}
			}
			$dataNews = $_POST ["dataNews1"] . ' ' . $this->f_ch_l ( $_POST ["dataNews2"] ) . ' ' . $_POST ["dataNews3"];
			$_POST ["dataNews"] = strtotime ( $dataNews );
			unset ( $_POST ["dataNews1"] );
			unset ( $_POST ["dataNews2"] );
			unset ( $_POST ["dataNews3"] );
			$sql1 = '';
			$sql2 = '';
			$i = 0;
			foreach ( $_POST as $name => $value ) {
				if ($i != 0) {
					$sql1 .= ",";
				}
				$sql1 .= "`{$name}`='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $value ) ) ) . "'";
				$i ++;
			}
			$sql = "update News set {$sql1} where id='{$obj["id"]}'";
			$dbc->query ( $sql );
			return 'success';
		}
	}
	
	//Разделы
	public function cats() {
		global $smarty;
		$center = '';
		$arrayCat = $this->getStructureCat ( 0 );
		$center .= '<h4>Структура каталога:</h4>';
		foreach ( $arrayCat as $name => $value )
			$center .= $this->getStructureView ( $value );
		
		$smarty->assign ( 'content', $center );
		return $smarty->fetch ( 'admin_clear_1.tpl' );
	}
	
	//Создает структуру каталога
	private function getStructureCat($id = 0) {
		global $dbc;
		$str = array ();
		$res1 = $dbc->query ( "SELECT id, catName as name, catParent
	    FROM NewsCategorys
	    WHERE catParent = '$id'", 1 );
		//if (mysql_num_rows($res1) == 0) return false;
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$tmp = array (mysql_result ( $res1, $i, "name" ), mysql_result ( $res1, $i, "id" ) );
			$tmp2 = $this->getStructureCat ( mysql_result ( $res1, $i, "id" ) );
			$str [] = array ($tmp, $tmp2 );
			$str [$i] [] = mysql_result ( $res1, $i, "catParent" );
		}
		return $str;
	}
	
	//Создает структуру формы <select>
	private function getStructureSelect($array = false, $padding = '', $select = 0, $non = 0) {
		if ($non == $array [0] [1])
			return;
		$return = '<option value="' . $array [0] [1] . '"';
		if ($array [0] [1] == $select)
			$return .= ' selected';
		$return .= '>' . $padding . $array [0] [0];
		if (isset ( $array [1] ) && count ( $array [1] ) > 0)
			foreach ( $array [1] as $name => $value )
				$return .= $this->getStructureSelect ( $value, $padding . '&nbsp;&nbsp;', $select, $non );
		return $return;
	}
	
	//Создает структуру для просмотра
	private function getStructureView($array = false, $padding = '') {
		$return = '<div>' . $padding . '<a href="#mod=news&act=editcat&uid=' . $array [0] [1] . '">' . $array [0] [0] . '</a>&nbsp;(<a onclick="if (!confirm(\'Вы уверены, что хотите удалить категорию ?\'))return false;" style="color:red;" href="#mod=news&act=delcat&uid=' . $array [0] [1] . '">del</a>)<div>';
		if (isset ( $array [1] ) && count ( $array [1] ) > 0)
			foreach ( $array [1] as $name => $value )
				$return .= $this->getStructureView ( $value, $padding . '&nbsp;&nbsp;&nbsp;' );
		return $return;
	}
	
	public function addnewcat() {
		global $dbc, $smarty;
		if (! isset ( $_GET ["step"] )) {
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			$arrayCat = $this->getStructureCat ( 0 );
			foreach ( $arrayCat as $name => $value )
				$this->get_structure_select ( $value, '' );
			foreach ( $this->items2 as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $this->items_names2 [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = '';
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$items [$i] [2] [0] = array ("id" => 0, "name" => "--без раздела--" );
					$y = 1;
					foreach ( $GLOBALS ["_select"] as $value ) {
						$items [$i] [2] [$y] = array ("id" => $value [0], "name" => $value [1] );
						$y ++;
					}
				}
			}
			$smarty->assign ( 'action', "?mod=news&act=addnewcat&step=2" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2') {
			if (trim ( $_POST ["catName"] ) == '') {
				return 'Необходимо ввести название категории!';
			}
			$dbc->query ( "insert into NewsCategorys (catName, catParent, catStatus, catTranslit) values (
			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["catName"] ) ) ) . "',
			'" . intval ( $_POST ["catParent"] ) . "','" . intval ( $_POST ["catStatus"] ) . "',
			'" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_escape_string ( strtolower ( rusToTrans ( $_POST ["catName"] ) ) ) ) ) . "')" );
			return 'success';
		}
	}
	
	public function editcat() {
		global $dbc, $smarty;
		$header = '';
		
		if (isset ( $_GET ["uid"] ) and preg_match ( '/^[0-9]{0,11}$/', $_GET ["uid"] )) {
			$obj = $dbc->query ( "select * from NewsCategorys where id='" . $_GET ["uid"] . "'", 7 );
		} else {
			header ( "Location:?mod=news&act=cats" );
		}
		
		if (! isset ( $_GET ["step"] )) {
			
			//Забиваем айтемы
			$items = array ();
			$i = - 1;
			
			$arrayCat = $this->getStructureCat ( 0 );
			foreach ( $arrayCat as $name => $value )
				$this->get_structure_select ( $value, '' );
			
			foreach ( $this->items2 as $key => $val ) {
				$i ++;
				$items [$i] [0] = $val;
				$items [$i] [1] ["TITLE"] = $this->items_names2 [$key];
				$items [$i] [1] ["NAME"] = $key;
				$items [$i] [1] ["VALUE"] = str_replace ( '"', "&quot;", $obj [$key] );
				$items [$i] [1] ["HELP"] = '';
				if ($items [$i] [0] == 'select') {
					//Категории
					$items [$i] [2] = array ();
					$items [$i] [2] [0] = array ("id" => 0, "name" => "--без раздела--" );
					$y = 1;
					foreach ( $GLOBALS ["_select"] as $value ) {
						if ($value [0] == $obj ["id"]) {
							continue;
						}
						$items [$i] [2] [$y] = array ("id" => $value [0], "name" => $value [1] );
						if ($obj [$key] == $items [$i] [2] [$y] ["id"]) {
							$items [$i] [2] [$y] ["selected"] = "1";
						}
						$y ++;
					}
				}
			}
			$smarty->assign ( 'edit', "1" );
			$smarty->assign ( 'back', "mod=news&act=cats" );
			$smarty->assign ( 'action', "?mod=news&act=editcat&step=2&uid={$obj["id"]}" );
			$smarty->assign ( 'items', $items );
			return $smarty->fetch ( 'admin_add_l_1.tpl' );
		
		} elseif ($_GET ["step"] == '2') {
			
			if (trim ( $_POST ["catName"] ) == '') {
				return 'Необходимо ввести название категории!';
			}
			$dbc->query ( "update NewsCategorys set 
			catName='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_real_escape_string ( $_POST ["catName"] ) ) ) . "', 
			catParent='" . intval ( $_POST ["catParent"] ) . "', 
			catStatus='" . intval ( $_POST ["catStatus"] ) . "', 
			catTranslit='" . str_replace ( "'", "''", str_replace ( "\'", "'", mysql_escape_string ( strtolower ( rusToTrans ( $_POST ["catName"] ) ) ) ) ) . "'
			where id='{$obj["id"]}'" );
			return 'success';
		}
		return $center;
	}
	
	public function delcat() {
		global $dbc;
		
		if (! isset ( $_GET ["uid"] ) || ! preg_match ( "/^[0-9]{1,11}$/", $_GET ["uid"] )) {
			header ( "Location:?mod=news&act=cats" );
			exit ();
		}
		$objCat = $dbc->query ( "select * from NewsCategorys where id='" . $_GET ["uid"] . "'", 2 );
		if (! $objCat) {
			header ( "Location:?mod=news&act=cats" );
			exit ();
		}
		$res1 = $dbc->query ( "select id from NewsCategorys where catParent='$objCat->id'", 1 );
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$dbc->query ( "update NewsCategorys set catParent='$objCat->catParent' where id='" . mysql_result ( $res1, $i, "id" ) . "'" );
		}
		$dbc->query ( "delete from NewsCategorys where id='$objCat->id'" );
		header ( "Location:?mod=news&act=cats" );
		exit ();
	}
}

$module = new news ( );

$GLOBALS ['namemodule'] = $module->name;
?>