<?

if (! MODULE_SYS)
	die ( "Файл может работать только как модуль!" );
	
class cModules {
	
	static function getContent($module = '') {
		global $dbc;
		if (file_exists ( $GLOBALS ["path_modules"] . "module." . $module . ".php" )) {
			include ($GLOBALS ["path_modules"] . "module." . $module . ".php");
			if (function_exists ( $module . '_main' )) {
				eval ( "\$center=" . $module . "_main();" );
			} else {
				header ( "HTTP/1.1 404 Not Found" );
				header ( "Location:/404.html" );
				exit ();
			}
		} else {
			header ( "HTTP/1.1 404 Not Found" );
			header ( "Location:/404.html" );
			exit ();
		}
		return $center;
	}
}

?>