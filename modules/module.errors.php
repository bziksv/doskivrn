<?
if (! MODULE_SYS)
	die ( "Файл может работать только как модуль!" );
function errors_main() {
	global $dbc;
	
	if (! isset ( $_GET ["page"] ))
		$_GET ["page"] = '';
	$GLOBALS ["tpl"] = 'pages.tpl';
	switch ($_GET ["page"]) {
		case "404" :
			header ( "HTTP/1.0 404 Not Found" );
			$GLOBALS ['smarty']->assign ( 'pagename', "Ошибка 404. Страница не найдена." );
			return $GLOBALS ["smarty"]->fetch ( 'errors_404.tpl' );
			break;
		case "403" :
			header ( "HTTP/1.0 403 Forbidden" );
			$GLOBALS ['smarty']->assign ( 'pagename', "Ошибка 403. Доступ запрещен." );
			return $GLOBALS ["smarty"]->fetch ( 'errors_403.tpl' );
			break;
		case "401" :
			header ( "HTTP/1.0 401 Unauthorized" );
			$GLOBALS ['smarty']->assign ( 'pagename', "Ошибка 401. В авторизации отказано." );
			return $GLOBALS ["smarty"]->fetch ( 'errors_401.tpl' );
			break;
		case "500" :
			header ( "HTTP/1.0 500 Internal Server Error" );
			$GLOBALS ['smarty']->assign ( 'pagename', "Ошибка 500. Internal Server Error." );
			return $GLOBALS ["smarty"]->fetch ( 'errors_500.tpl' );
			break;
		default :
			exit ();
	}
}

?>