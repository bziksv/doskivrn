<?
//База данных
//+
class db {
	
	private $count = 10;
	public $name = 'Управление базой данных';
	
	public $methods = array ("Оптимизация базы данных" => "optimize" );
	
	public function optimize() {
		global $dbc, $smarty;
		$smarty->assign ( 'pagename','Оптимизация таблиц базы данных ' . $GLOBALS ['dbname'] . '' );
		$result = mysql_list_tables ( $GLOBALS ['dbname'] );
		if (! isset ( $_GET ["step"] )) {
			$center = '<a href="#mod=db&act=optimize&step=2">Начать оптимизацию таблиц базы данных</a>';
		} elseif ($_GET ["step"] == '2') {
			$center = 'Таблицы:<br/><br/>';
			if (! $result) {
				$center .= "DB Error, could not list tables\n";
				$center .= 'MySQL Error: ' . mysql_error ();
			}
			while ( $row = mysql_fetch_row ( $result ) ) {
				$resp = $dbc->query ( "OPTIMIZE TABLE $row[0]", 2 );
				$center .= "Table {$resp->Table } {$resp->Op}: {$resp->Msg_text }<br>";
			}
		}
		$smarty->assign ( 'content', $center );
		return $smarty->fetch ( 'admin_clear_1.tpl' );
	}
}

$module = new db ( );

$GLOBALS ['namemodule'] = $module->name;
?>