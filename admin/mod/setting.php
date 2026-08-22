<?

//Настройки сайта


//+++
class setting {
	
	private $count = 10;
	
	public $name = 'Настройки';
	
	public $methods = array ("Список настроек" => 'listsetting' );
	
	public function listsetting() {
		global $dbc, $smarty;
		
		if (isset ( $_GET ["step"] ) && $_GET ["step"] == '2') {
			foreach ( $_POST as $name => $value ) {
				$dbc->query ( "update setting set value='" . $value . "' where name='" . $name . "'" );
			}
			exit();
		}
		
		$res_adm = $dbc->query ( "select * from setting", 1 );
		$center = '<script>edit=1;import_data = false;</script><form id="form_edit" action="?mod=setting&act=listsetting&step=2" onsubmit="f_save(\'form_edit\',\'?mod=setting&act=listsetting&step=2\'); return false;" method="post">
		<table class="xajax_add_boxt">';
		for($i = 0; $i < mysql_num_rows ( $res_adm ); $i ++) {
			$data_op = mysql_fetch_object ( $res_adm );
			$center .= '
			<tr>
				<td class="text">' . $data_op->description . ':</td>
				<td class="input_text"><input type="text" name="' . $data_op->name . '" value="' . str_replace ( '"', "&quot;", $data_op->value ) . '"/></td>
			</tr>
			';
		}
		$center .= '<tr>
		<td class="text">&nbsp;</td>
			<td><div class="form_button"><input type="submit" value="Сохранить" /></div></td>
		</tr>
		</table></form>';
		$GLOBALS ['smarty']->assign ( "content", $center );
		return $smarty->fetch ( 'admin_clear_1.tpl' );
	}

}

$module = new setting ( );

$GLOBALS ['namemodule'] = $module->name;
?>