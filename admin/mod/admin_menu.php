<?
//Администраторы
//+


class admin_menu {
	/**
	 * Парсит SIMPLEXML меню и дает структуру в виде массива
	 *
	 * @param simlexml $menu
	 */
	private function get_structure_menu($menu) {
		global $dbc;
		$array = array ();
		$i = 0;
		$group_id = $dbc->query ( "select accessLevel from Admins where login='" . htmlspecialchars ( $_SESSION ["nick"], ENT_QUOTES ) . "'", 6 );
		foreach ( $menu as $value ) {
			$tmp = explode ( "&", $value ["link"] [0] );
			if (count ( $tmp ) > 1) {
				$tmp [0] = htmlspecialchars ( str_replace ( "mod=", "", $tmp [0] ), ENT_QUOTES );
				$tmp [1] = htmlspecialchars ( str_replace ( "act=", "", $tmp [1] ), ENT_QUOTES );
				if ($dbc->query ( "SELECT count(admins_list_methods.id)
					FROM admins_list_methods
					INNER JOIN admins_methods_group
					ON admins_methods_group.l_group = '{$group_id}'
					AND admins_methods_group.l_method = admins_list_methods.id
					
					WHERE admins_list_methods.l_module='{$tmp [0]}' and admins_list_methods.l_method='{$tmp [1]}'", 6 ) == 0) {
					continue;
				}
			}
			
			$line = (isset ( $value ["line"] ) && $value ["line"]) ? 1 : 0;
			$open = (isset ( $value ["open"] )) ? 1 : 0;
			$openlink = (isset ( $value ["linkopen"] )) ? "" . $value ["linkopen"] : "";
			$array [$i] = array ("openlink" => $openlink, "open" => $open, "link" => "" . $value ["link"] [0], "num" => "" . ($i+1), "name" => "" . $value ["name"] [0], "help" => "" . $value ["help"] [0], "line" => $line );
			if (isset ( $value->menu_item )){
				$ppp = $this->get_structure_menu ( $value->menu_item );
				if (count($ppp) > 0){
					$array [$i] ["childrens"] = $ppp;
				}
			}
			$i ++;
		}
		return $array;
	}
	private function tpl_assign($name, $value) {
		$GLOBALS ['smarty']->assign ( $name, $value );
	}
	private function tpl_fetch($tpl_name) {
		return $GLOBALS ['smarty']->fetch ( $tpl_name );
	
	}
	/**
	 * Базовая функция
	 *
	 */
	public function init() {
		//Парсим файл меню
		$menu = simplexml_load_file ( $_SERVER ['DOCUMENT_ROOT'] . '/admin/menu.xml' ); //, "SimpleXMLElement"
		//Генерируем массив меню
		$menu = $this->get_structure_menu ( $menu );
		//Создаем HTML меню
		$menu = $this->get_html_menu ( $menu );
		//Отдаем браузеру
		print $menu;
		exit ();
	}
	private $numeric = 0;
	/**
	 * Формирует HTML меню
	 *
	 * @param array $menu
	 */
	private function get_html_menu($menu) {
		$html = '';
		//Проходимся по массиву и формируем HTML
		foreach ( $menu as $key => $value ) {
			$value ["childrens"] = (isset ( $value ["childrens"] )) ? $this->get_html_menu ( $value ["childrens"] ) : "";
			$this->numeric ++;
			$this->tpl_assign ( "numeric", $this->numeric );
			$this->tpl_assign ( "menu_all", "0" );
			$this->tpl_assign ( "menu_item", "1" );
			$this->tpl_assign ( "menu_item_info", $value );
			$html .= $this->tpl_fetch ( 'admin_menu.tpl' );
		}
		$this->tpl_assign ( "menu_all", "1" );
		$this->tpl_assign ( "menu_item", "0" );
		$this->tpl_assign ( "menu_all_info", $html );
		$html = $this->tpl_fetch ( 'admin_menu.tpl' );
		return $html;
	}
	/**
	 * Создает субменю по требованию
	 *
	 * @param array $menu;
	 */
	public function set_submenu($menu) {
		print $this->get_html_menu ( $menu );
	}

}
$module = new admin_menu ( );
?>