<?php

class getcontent {
	
	static public function _get($link = '') {
		global $dbc;
		if ($link == '') {
			return;
		}
		return $dbc->query ( "SELECT `pageContent` FROM `Pages` WHERE `link` = '{$link}'", 6 );
	}
	
	static public function _get_setting($name = '') {
		global $dbc;
		if ($name == '') {
			return;
		}
		return $dbc->query ( "SELECT `value` FROM `setting` WHERE `name` = '{$name}'", 6 );
	}

}

?>