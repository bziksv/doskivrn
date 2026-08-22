<?
	
	define("EXCEPTION_LEVEL_TRIGGER", "1"); # 0 - молчать, 1 - показывать красивое окошко

	function debug_sys_handler($severity, $msg, $file, $line) {
		$exit=1;
		switch ($severity) {
			case E_ERROR:           $err_const='Fatal error'; $exit=0;break;
			case E_WARNING:         $err_const='Warning';$exit=0;break;
			case E_PARSE:           $err_const='Parse error';$exit=0;break;
			case E_NOTICE:          $err_const='Notice';$exit=0;break;
			case E_USER_ERROR:      $err_const='Fatal error(user-generated)'; $exit=0;break;
			case E_USER_WARNING:    $err_const='Warning(user-generated)';$exit=0;break;
			case E_USER_NOTICE:     $err_const='Notice(user-generated)'; $exit=0;break;
		}
		if ($exit)return;
		if (EXCEPTION_LEVEL_TRIGGER > 0){
			display_error($severity, $msg, $file, $line,$err_const);
		}
		else return;
  }
	
	function display_error($severity, $msg, $file, $line,$err_const){
			print'<table cellspacing=0 style="border: 1px solid #333333" cellpadding=3 width="1%" height="1%" border=0>
				   <tr height="1%" bgcolor="#FF0000"><td nowrap align="center" style="border-bottom:1px Solid #000000;"><font color="#FFFFFF" style="font-size:10px;font-family:Tahoma;font-weight:bold;"><b>
			Ошибка #'.$severity.' ('.$err_const.')
			</b></font></td>
				   </tr><tr bgcolor="#FFFFFF"><td nowrap style="padding:3px;"><font style="font-size:10px;font-family:Tahoma">';
		  $message = "File: $file<br><br>Line: $line<br><br>$msg<br><br>Пожалуйста напишите о данной ошибке администрации!<br>Please mail for support about this error!";
		  print($message);
		  print '</font></td></tr></table>';
	}
	//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
	$error_handler = set_error_handler('debug_sys_handler');
	print $error_handler;
?>