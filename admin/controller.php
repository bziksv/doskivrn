<?
//маленький контроллер
class controllerAdmin {
	private $module;
	private $action; //Действие это метод в классе модуля с префиксом Admin
	

	private $loginAdmin;
	
	public function __construct() {
		global $dbc;
		if (isset ( $_POST ["EnterLogin"] ) and isset ( $_POST ["EnterPassword"] )) {
			$dbc->query ( "select count(id) from Admins where login='" . htmlspecialchars ( $_POST ["EnterLogin"] ) . "' and password='" . md5 ( $_POST ["EnterPassword"] ) . "'" );
			if ($dbc->getResult ( 0, 0 ) == '0') {
				$GLOBALS ['smarty']->assign ( "ref", '?' );
				$GLOBALS ['smarty']->assign ( "err", 'Неправильно введет логин или пароль!' );
				$GLOBALS ['smarty']->display ( "admin_enter.tpl" );
				exit ();
			} else {
				$_SESSION ["nick"] = $_POST ["EnterLogin"];
				$_SESSION ["adminLogin"] = true;
				if (isset ( $_SERVER ["HTTP_REFERER"] )) {
					header ( "Location:" . $_SERVER ["HTTP_REFERER"] );
				}
			}
		
		}
		//Проверяем, залогинен ли администратор. если нет, тогда выдает ему форму входа
		if (isset ( $_SESSION ["adminLogin"] ) and $_SESSION ["adminLogin"] and isset ( $_SESSION ["nick"] )) {
			$dbc->query ( "select id from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"] ) . "'" );
			if ($dbc->getResult ( 0, 0 ) == '' or $dbc->getResult ( 0, 0 ) == null) {
				header ( "Location:http://" . $_SERVER ['HTTP_HOST'] . "/" );
				exit ();
			}
			$this->loginAdmin = htmlspecialchars ( $_SESSION ["nick"] );
		} else {
			$GLOBALS ['smarty']->assign ( "ref", '?' );
			$GLOBALS ['smarty']->assign ( "err", '' );
			$GLOBALS ['smarty']->display ( "admin_enter.tpl" );
			exit ();
		}
		/**
		 * Исключения
		 */
		$nexts = array ("admin_menu" );
		$group_id = $dbc->query ( "select accessLevel from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"], ENT_QUOTES ) . "'", 6 );
		if (isset ( $_GET ["mod"] ) && isset ( $_GET ["act"] ) && ! in_array ( $_GET ["mod"], $nexts )) {
			/**
			 * Проверяем, есть ли доступ к этому действию
			 */
			$_GET ["act"] = htmlspecialchars ( $_GET ["act"], ENT_QUOTES );
			$_GET ["mod"] = htmlspecialchars ( $_GET ["mod"], ENT_QUOTES );
			if ($dbc->query ( "SELECT count(admins_list_methods.id)
					FROM admins_list_methods
					INNER JOIN admins_methods_group
					ON admins_methods_group.l_group = '{$group_id}'
					AND admins_methods_group.l_method = admins_list_methods.id
					
					WHERE admins_list_methods.l_module='{$_GET ["mod"]}' and admins_list_methods.l_method='{$_GET ["act"]}'", 6 ) == 0) {
				$GLOBALS ["center"] = '<div class="xjaxcontent"><div class="xajax_tablelist"><br /><br />У Вас недостаточно прав администратора для совершения данного действия!<br /><br /><a href="javascript:history.back()">&laquo; вернуться назад</a></div></div>';
				print $GLOBALS ["center"];
				exit ();
			}
		} elseif (isset ( $_GET ["mod"] ) && ! in_array ( $_GET ["mod"], $nexts )) {
			$GLOBALS ["center"] = '<div class="xjaxcontent"><div class="xajax_tablelist"><br /><br />Добро пожаловать в панель администратора.</div></div>';
			print $GLOBALS ["center"];
			exit ();
		}
		
		//Смотрим какой нам нужен модуль и подсоединяем его
		if (isset ( $_GET ["mod"] ) and preg_match ( "/^[a-zA-Z0-9_]{1,15}$/", $_GET ["mod"] )) {
			// __DIR__: cwd при php -S / router — корень сайта, не admin/
			$mod_file = __DIR__ . '/mod/' . $_GET ["mod"] . '.php';
			if (file_exists ( $mod_file )) {
				include $mod_file;
			} else {
				$GLOBALS ["center"] = '<div class="xjaxcontent"><div class="xajax_tablelist"><br /><br />Данный модуль не найден!<br /><br /><a href="javascript:history.back()">&laquo; вернуться назад</a></div></div>';
				print $GLOBALS ["center"];
				exit ();
			}
		} else {
			return;
		}
		
		//Автоматически должен был инициализировать класс модуля, ежели этого нет, тогда говорим это
		if (! isset ( $module )) {
			$GLOBALS ["center"] = '<div class="xjaxcontent"><div class="xajax_tablelist"><br /><br />Данный модуль содержит ошибки, всвязи с которыми дальнейшая работа невозможна!<br /><br /><a href="javascript:history.back()">&laquo; вернуться назад</a></div></div>';
			print $GLOBALS ["center"];
			exit ();
		}
		
		//Смотрим какое действие нам нужно, и активируем его
		if (isset ( $_GET ["act"] ) and $_GET ["act"] != null and $_GET ["act"] != '') {
			$act = $_GET ["act"];
			$methods = get_class_methods ( $module );
			if (in_array ( $act, $methods, true ) || in_array ( strtolower ( $act ), array_map ( 'strtolower', $methods ), true )) {
				$GLOBALS ["center"] = $module->$act ();
			} else {
				$GLOBALS ["center"] = '<div class="xjaxcontent"><div class="xajax_tablelist"><br /><br />Ошибка! Данного действия не существует!<br /><br /><a href="javascript:history.back()">&laquo; вернуться назад</a></div></div>';
				print $GLOBALS ["center"];
				exit ();
			}
		} else {
			$GLOBALS ['center'] = $module->main ();
		}
		
		if (isset ( $_GET ["mod"] ) && $_GET ["mod"] != '') {
			print $GLOBALS ['center'];
			exit ();
		}
	}

}

?>