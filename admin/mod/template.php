<?
class template {
	
	private $count = 10;
	public $name = 'Шаблоны сайта';
	
	public $methods = array ("Просмотр файлов шаблона" => "listhtml", "Редактирование шаблона" => "edithtml" );
	private $items = array ("main" => "text" );
	
	private $items_names = array ("main" => "Содержимое файла" );
	public function listhtml() {
		global $dbc, $smarty;
		$smarty->assign ( 'pagename', 'Выберите файл для редактирования' );
		$_GET ["tpl"] = 'default';
		$center = '';
		$files = array ();
		if ($handle = opendir ( $GLOBALS ['global_path'] . '/templates/' . $_GET ["tpl"] )) {
			while ( false !== ($file = readdir ( $handle )) ) {
				if ($file != "." && $file != "..") {
					$files [] = $file;
				}
			}
			closedir ( $handle );
			sort ( $files, SORT_STRING );
			foreach ( $files as $file ) {
				$center .= '<div>- <a href="#mod=template&act=edithtml&file=' . $file . '">' . $file . '</a></div>';
			}
		}
		
		$smarty->assign ( 'content', $center );
		return $smarty->fetch ( 'admin_clear_1.tpl' );
	}
	
	public function edithtml() {
		global $smarty;
		$_GET ["tpl"] = 'default';
		if (isset ( $_GET ["tpl"] )) {
			if (is_file ( $GLOBALS ['global_path'] . 'templates/' . $_GET ["tpl"] . '/' . $_GET ["file"] )) {
				$file = $GLOBALS ['global_path'] . 'templates/' . $_GET ["tpl"] . '/' . $_GET ["file"];
				if (file_exists ( $file ) and is_writable ( $file )) {
					if (! isset ( $_GET ["step"] ) || isset ( $_POST ["savefile"] ) && trim ( $_POST ["savefile"] ) == '') {
						$file_c = file_get_contents ( $file );
						$file_c = str_replace ( ">", "&gt;", $file_c );
						$file_c = str_replace ( "<", "&lt;", $file_c );
						//Забиваем айтемы
						$items = array ();
						$i = - 1;
						foreach ( $this->items as $key => $val ) {
							$i ++;
							$items [$i] [0] = $val;
							$items [$i] [1] ["TITLE"] = $this->items_names [$key];
							$items [$i] [1] ["NAME"] = $key;
							$items [$i] [1] ["VALUE"] = $file_c;
							$items [$i] [1] ["HELP"] = '';
						}
						$smarty->assign ( 'back', "mod=template&act=listhtml" );
						$smarty->assign ( 'edit', "1" );
						$smarty->assign ( 'action', "?mod=template&act=edithtml&file={$_GET ["file"]}&step=2" );
						$smarty->assign ( 'items', $items );
						return $smarty->fetch ( 'admin_add_l_2.tpl' );
					
					} elseif ($_GET ["step"] == '2') {
						$error = 0;
						$file_c = $_POST ["main"];
						if (trim ( $_POST ["main"] ) == '') {
							header ( "Location:?mod=template&act=listhtml" );
							exit ();
						}
						$file_c = str_replace ( "&gt;", ">", $file_c );
						$file_c = str_replace ( "&lt;", "<", $file_c );
						if (! $handle = fopen ( $file, 'w' )) {
							$center = "Не возможно открыть файл.";
							$error = 1;
						}
						if ($error == 0 && fwrite ( $handle, $file_c ) === FALSE) {
							$center = "Не возможно произвести запись в файл.";
							$error = 1;
						}
						if ($error == 0)
							$center = 'Файл удачно сохранен!';
						
						fclose ( $handle );
						return '';
					}
				
				} else
					$center = 'Вероятно вы ошиблись! Файл не найден или в него невозможно произвести запись!<br /><br /><a href="javascript:history.back()">&laquo; вернуться назад</a>';
			} else
				$center = 'Вероятно вы ошиблись! Файл не найден!<br /><br /><a href="javascript:history.back()">&laquo; вернуться назад</a>';
		} else
			$center = 'Вероятно вы ошиблись! Файл не найден!<br /><br /><a href="javascript:history.back()">&laquo; вернуться назад</a>';
		$smarty->assign ( 'content', $center );
		return $smarty->fetch ( 'admin_clear_1.tpl' );
	}
}

$module = new template ( );

$GLOBALS ['namemodule'] = $module->name;
?>