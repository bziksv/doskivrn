<?php

if (! MODULE_SYS)
	die ( "Файл может работать только как модуль!" );

function order_main() {
	global $dbc;
	$GLOBALS ['tpl'] = 'pages.tpl';
	if (! isset ( $_GET ["page"] ))
		$_GET ["page"] = '';
	switch ($_GET ["page"]) {
		default :
			return feedback_default ();
			exit ();
	}

}

function feedback_default() {
	global $dbc;
	$GLOBALS ["smarty"]->assign ( 'pagename', "ON-LINE ЗАЯВКА" );
	$GLOBALS ["title"] = "ON-LINE ЗАЯВКА";
	
	$error = '';
	
	if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
		//Мыло
		if (! isset ( $_POST ["mail"] ) || ! eregi ( "^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $_POST ["mail"] )) {
			$error .= 'Некорректный e-mail.<br />';
		}
		//Имя
		if (trim ( strlen ( $_POST ["name"] ) ) == 0) {
			$error .= 'Необходимо указать Ваше имя.<br />';
		} else {
			$_POST ["name"] = htmlspecialchars ( $_POST ["name"], ENT_QUOTES );
		}
		//Правило
		if (trim ( strlen ( $_POST ["rule"] ) ) == 0) {
			$error .= 'Вы не отметили обработку персональных данных.<br />';
		}
		//Телефон
		if (trim ( strlen ( $_POST ["phone"] ) ) == 0) {
			$error .= 'Необходимо указать Ваш телефон.<br />';
		} else {
			$_POST ["phone"] = htmlspecialchars ( $_POST ["phone"], ENT_QUOTES );
		}
		
		if (empty ( $error )) {
			$message = $GLOBALS ['smarty']->fetch ( 'mail_tpl_order.tpl' );
			mailsend ( "Заказ", $dbc->query ( "select value from setting where name='mailfeedback'", 6 ), $message );
			$message2 = 'Ваша заявка успешно отправлена.';
			unset ( $_POST );
			$_POST = array ();
		}
	
	}
	if (! empty ( $error )) {
		$GLOBALS ['smarty']->assign ( 'error', $error );
	}
	if (isset ( $message2 )) {
		$GLOBALS ['smarty']->assign ( 'message', $message2 );
	}
	return $GLOBALS ["smarty"]->fetch ( 'order_main.tpl' );
}

?>