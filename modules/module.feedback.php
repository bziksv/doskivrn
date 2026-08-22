<?php



if (! MODULE_SYS)

	die ( "Файл может работать только как модуль!" );



function feedback_main() {

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



function feedback_sanitize_post() {

	if (! is_array ( $_POST ))

		return;

	foreach ( array ('name', 'company', 'phone', 'mail', 'msg', 'code') as $field ) {

		if (isset ( $_POST [$field] )) {

			$_POST [$field] = htmlspecialchars ( trim ( $_POST [$field] ), ENT_QUOTES );

		}

	}

}



function feedback_is_form_post() {

	return $_SERVER ['REQUEST_METHOD'] === 'POST' && isset ( $_POST ['feedback_form'] );

}



function feedback_handle_request() {

	global $dbc;

	$errors = array ();

	$message = '';



	if (! feedback_is_form_post ()) {

		$GLOBALS ['smarty']->assign ( 'feedback_errors', $errors );

		$GLOBALS ['smarty']->assign ( 'feedback_message', $message );

		return;

	}



	feedback_sanitize_post ();



	if (! isset ( $_SESSION ['rndCodeImg'] ) || $_SESSION ['rndCodeImg'] === '') {

		$errors ['code'] = 'Обновите код проверки и введите ответ.';

	} else {

		$expr = $_SESSION ['rndCodeImg'];

		unset ( $_SESSION ['rndCodeImg'] );

		$expected = null;

		if (preg_match ( '/^(\d)([\+\-])(\d)$/', $expr, $m )) {

			$a = (int) $m [1];

			$b = (int) $m [3];

			$expected = ($m [2] === '+') ? $a + $b : $a - $b;

		}

		$code = isset ( $_POST ['code'] ) ? $_POST ['code'] : '';

		if ($code === '' || $expected === null || (int) $code !== $expected) {

			$errors ['code'] = 'Неверный код проверки.';

		}

	}



	if (! isset ( $_POST ['name'] ) || $_POST ['name'] === '') {

		$errors ['name'] = 'Укажите ваше имя.';

	}



	if (! isset ( $_POST ['phone'] ) || ! preg_match ( '/^\+7-\d{3}-\d{3}-\d{2}-\d{2}$/', $_POST ['phone'] )) {

		$errors ['phone'] = 'Укажите телефон в формате +7-___-___-__-__.';
		unset ( $_POST ['phone'] );

	}



	if (! isset ( $_POST ['mail'] ) || $_POST ['mail'] === '' || ! preg_match ( "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $_POST ['mail'] )) {

		$errors ['mail'] = 'Укажите корректный e-mail.';

	}



	if (! isset ( $_POST ['msg'] ) || $_POST ['msg'] === '') {

		$errors ['msg'] = 'Напишите текст сообщения.';

	}



	if (! isset ( $_POST ['rule'] ) || $_POST ['rule'] === '') {

		$errors ['rule'] = 'Подтвердите согласие на обработку данных.';

	}



	if (empty ( $errors )) {

		$message = $GLOBALS ['smarty']->fetch ( 'mail_tpl_feedback.tpl' );

		mailsend ( "ON-LINE ЗАЯВКА", $dbc->query ( "select value from setting where name='mailfeedback'", 6 ), $message );

		$message = 'Ваша заявка успешно отправлена.';

		$_POST = array ();

	}



	$GLOBALS ['smarty']->assign ( 'feedback_errors', $errors );

	$GLOBALS ['smarty']->assign ( 'feedback_message', $message );

}



function feedback_default() {

	$GLOBALS ["smarty"]->assign ( 'pagename', "ON-LINE ЗАЯВКА" );

	$GLOBALS ["title"] = "ON-LINE ЗАЯВКА";

	return $GLOBALS ["smarty"]->fetch ( 'feedback_main.tpl' );

}



?>
