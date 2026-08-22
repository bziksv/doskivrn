<?php

if (!defined('MODULE_SYS') || !MODULE_SYS)
	die ( "Файл может работать только как модуль!" );

function createImage($uploadedfile, $newsize, $name) {
	if (! $info = getimagesize ( $uploadedfile ))
		return false;
	switch ($info ['mime']) {
		case 'image/jpeg' :
			$src = imagecreatefromjpeg ( $uploadedfile );
			break;
		case 'image/gif' :
			$src = imagecreatefromgif ( $uploadedfile );
			break;
		case 'image/png' :
			$src = imagecreatefrompng ( $uploadedfile );
			break;
		default :
			return false;
	}
	$size = getimagesize ( $uploadedfile );
	if ($size [0] > $size [1]) {
		$newWidth = $newsize;
		$per = $newsize / $size [0];
		$newHeight = $size [1] * $per;
	} elseif ($size [1] > $size [0]) {
		$newHeight = $newsize;
		$per = $newsize / $size [1];
		$newWidth = $size [0] * $per;
	}
	
	$tmp = imagecreatetruecolor ( $newWidth, $newHeight );
	imagecopyresampled ( $tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $info [0], $info [1] );
	switch ($info ['mime']) {
		case 'image/jpeg' :
			imagejpeg ( $tmp, $uploadedfile, 100 );
			break;
		case 'image/gif' :
			imagegif ( $tmp, $uploadedfile, 100 );
			break;
		case 'image/png' :
			imagepng ( $tmp, $uploadedfile );
			break;
	}
	imagedestroy ( $src );
	imagedestroy ( $tmp );
	return true;
}

function getRandom($length) {
	$chars = array ('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '[', ']', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', 'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'X', 'C', 'V', 'B', 'N', 'M', '`', '~', '@', '#', '%', '^', ':', '&', '*', '(', ')', '?', '!', '/', '|', '+', '-', '=', '_' );
	$newRandom = '';
	for($i = 0; $i < $length; $i ++) {
		$newRandom .= $chars [rand ( 0, (count ( $chars ) - 1) )];
	}
	return $newRandom;
}

//Только цифры и латинские буквы (для паролей как правило)
function getRandom2($length) {
	$chars = array ('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'X', 'C', 'V', 'B', 'N', 'M' );
	$newRandom = '';
	for($i = 0; $i < $length; $i ++) {
		$newRandom .= $chars [rand ( 0, (count ( $chars ) - 1) )];
	}
	return $newRandom;
}

//Отдает отформатированную дату
function getFormatData($time) {
	$day = date ( "d", $time );
	$mon = date ( "m", $time );
	$year = date ( "Y", $time );
	switch ($mon) {
		case "01" :
			$mon = 'января';
			break;
		case "02" :
			$mon = 'февраля';
			break;
		case "03" :
			$mon = 'марта';
			break;
		case "04" :
			$mon = 'апреля';
			break;
		case "05" :
			$mon = 'мая';
			break;
		case "06" :
			$mon = 'июня';
			break;
		case "07" :
			$mon = 'июля';
			break;
		case "08" :
			$mon = 'августа';
			break;
		case "09" :
			$mon = 'сентября';
			break;
		case "10" :
			$mon = 'октября';
			break;
		case "11" :
			$mon = 'ноября';
			break;
		case "12" :
			$mon = 'декабря';
			break;
	}
	return $day . ' ' . $mon . ' ' . $year;
}

function getFormatData2($time) {
	$day = date ( "d", $time );
	$mon = date ( "m", $time );
	$year = date ( "Y", $time );
	switch ($mon) {
		case "01" :
			$mon = 'января';
			break;
		case "02" :
			$mon = 'февраля';
			break;
		case "03" :
			$mon = 'марта';
			break;
		case "04" :
			$mon = 'апреля';
			break;
		case "05" :
			$mon = 'мая';
			break;
		case "06" :
			$mon = 'июня';
			break;
		case "07" :
			$mon = 'июля';
			break;
		case "08" :
			$mon = 'августа';
			break;
		case "09" :
			$mon = 'сентября';
			break;
		case "10" :
			$mon = 'октября';
			break;
		case "11" :
			$mon = 'ноября';
			break;
		case "12" :
			$mon = 'декабря';
			break;
	}
	return array ($day, $mon, $year );
}

//Формирование списка
function getListing($last, $current, $link) {
	$GLOBALS ["smarty"]->assign ( 'listing_count', $last );
	if ($last <= 1)
		return '';
	$return = '';
	
	$start = $current - 3;
	$end = $current + 3;
	if ($start < 1)
		$start = 1;
	if ($end > $last)
		$end = $last;
	
	if ($start > 1) {
		$return = '<a href="' . $link . '1">1</a><span>...</span>';
	}
	for($i = $start; $i <= $end; $i ++) {
		if ($i == $current) {
			$return .= '<span>' . $i . '</span>';
		} else {
			$return .= '<a href="' . $link . $i . '">' . $i . '</a>';
		}
	
	}
	if ($end < $last) {
		$return .= '<span>...</span><a href="' . $link . $last . '">' . $last . '</a>';
	}
	
	if ($current > 1) {
		$GLOBALS ["smarty"]->assign ( 'listing_prev', $link . ($current - 1) );
	} else {
		$GLOBALS ["smarty"]->assign ( 'listing_prev', "#" );
	}
	if ($current < $last) {
		$GLOBALS ["smarty"]->assign ( 'listing_next', $link . ($current + 1) );
	} else {
		$GLOBALS ["smarty"]->assign ( 'listing_next', "#" );
	}
	
	return $return;
}

//******************Работа с транслитом****************
function rusToTrans($data) {
	$data = str_replace ( "й", "y", $data );
	$data = str_replace ( "ц", "c", $data );
	$data = str_replace ( "у", "u", $data );
	$data = str_replace ( "к", "k", $data );
	$data = str_replace ( "е", "e", $data );
	$data = str_replace ( "н", "n", $data );
	$data = str_replace ( "г", "g", $data );
	$data = str_replace ( "ш", "sh", $data );
	$data = str_replace ( "щ", "sch", $data );
	$data = str_replace ( "з", "z", $data );
	$data = str_replace ( "х", "h", $data );
	$data = str_replace ( "ъ", "", $data );
	$data = str_replace ( "ф", "f", $data );
	$data = str_replace ( "ы", "y", $data );
	$data = str_replace ( "в", "v", $data );
	$data = str_replace ( "а", "a", $data );
	$data = str_replace ( "п", "p", $data );
	$data = str_replace ( "р", "r", $data );
	$data = str_replace ( "о", "o", $data );
	$data = str_replace ( "л", "l", $data );
	$data = str_replace ( "д", "d", $data );
	$data = str_replace ( "ж", "j", $data );
	$data = str_replace ( "э", "e", $data );
	$data = str_replace ( "я", "ya", $data );
	$data = str_replace ( "ч", "ch", $data );
	$data = str_replace ( "с", "s", $data );
	$data = str_replace ( "м", "m", $data );
	$data = str_replace ( "и", "i", $data );
	$data = str_replace ( "т", "t", $data );
	$data = str_replace ( "ь", "", $data );
	$data = str_replace ( "б", "b", $data );
	$data = str_replace ( "ю", "yu", $data );
	$data = str_replace ( "ё", "e", $data );
	$data = str_replace ( "Й", "Y", $data );
	$data = str_replace ( "Ц", "C", $data );
	$data = str_replace ( "У", "U", $data );
	$data = str_replace ( "К", "K", $data );
	$data = str_replace ( "Е", "E", $data );
	$data = str_replace ( "Н", "N", $data );
	$data = str_replace ( "Г", "G", $data );
	$data = str_replace ( "Ш", "SH", $data );
	$data = str_replace ( "Щ", "SCH", $data );
	$data = str_replace ( "З", "Z", $data );
	$data = str_replace ( "Х", "H", $data );
	$data = str_replace ( "Ъ", "", $data );
	$data = str_replace ( "Ф", "F", $data );
	$data = str_replace ( "Ы", "Y", $data );
	$data = str_replace ( "В", "V", $data );
	$data = str_replace ( "А", "A", $data );
	$data = str_replace ( "П", "P", $data );
	$data = str_replace ( "Р", "R", $data );
	$data = str_replace ( "О", "O", $data );
	$data = str_replace ( "Л", "L", $data );
	$data = str_replace ( "Д", "D", $data );
	$data = str_replace ( "Ж", "J", $data );
	$data = str_replace ( "Э", "E", $data );
	$data = str_replace ( "Я", "YA", $data );
	$data = str_replace ( "Ч", "CH", $data );
	$data = str_replace ( "С", "S", $data );
	$data = str_replace ( "М", "M", $data );
	$data = str_replace ( "И", "I", $data );
	$data = str_replace ( "Т", "T", $data );
	$data = str_replace ( "Ь", "", $data );
	$data = str_replace ( "Б", "B", $data );
	$data = str_replace ( "Ю", "YU", $data );
	$data = str_replace ( "Ё", "E", $data );
	$data = str_replace ( " ", "_", $data );
	$data = str_replace ( ",", "", $data );
	$data = str_replace ( ".", "", $data );
	$data = str_replace ( "(", "", $data );
	$data = str_replace ( ")", "", $data );
	$data = str_replace ( ":", "", $data );
	$data = str_replace ( ";", "", $data );
	$data = str_replace ( "\"", "", $data );
	$data = str_replace ( "'", "", $data );
	$data = str_replace ( "/", "", $data );
	return $data;
}

/*
q a z w s x e d c r f v t g b y h n u j m i k o l p
q а з w с x е д ц р ф в т г б й х н у ж м и к о л п
*/

function transToRus($data) {
	$data = str_replace ( "sh", "ш", $data );
	$data = str_replace ( "q", "q", $data );
	$data = str_replace ( "a", "а", $data );
	$data = str_replace ( "z", "з", $data );
	$data = str_replace ( "w", "w", $data );
	$data = str_replace ( "s", "с", $data );
	$data = str_replace ( "x", "x", $data );
	$data = str_replace ( "e", "е", $data );
	$data = str_replace ( "d", "д", $data );
	$data = str_replace ( "c", "ц", $data );
	$data = str_replace ( "r", "р", $data );
	$data = str_replace ( "f", "ф", $data );
	$data = str_replace ( "v", "в", $data );
	$data = str_replace ( "t", "т", $data );
	$data = str_replace ( "g", "г", $data );
	$data = str_replace ( "b", "б", $data );
	$data = str_replace ( "y", "й", $data );
	$data = str_replace ( "h", "х", $data );
	$data = str_replace ( "n", "н", $data );
	$data = str_replace ( "u", "у", $data );
	$data = str_replace ( "j", "ж", $data );
	$data = str_replace ( "m", "м", $data );
	$data = str_replace ( "i", "и", $data );
	$data = str_replace ( "k", "к", $data );
	$data = str_replace ( "o", "о", $data );
	$data = str_replace ( "l", "л", $data );
	$data = str_replace ( "p", "п", $data );
	
	$data = str_replace ( "SH", "Ш", $data );
	$data = str_replace ( "Q", "Q", $data );
	$data = str_replace ( "A", "А", $data );
	$data = str_replace ( "Z", "З", $data );
	$data = str_replace ( "W", "W", $data );
	$data = str_replace ( "S", "С", $data );
	$data = str_replace ( "X", "X", $data );
	$data = str_replace ( "E", "Е", $data );
	$data = str_replace ( "D", "Д", $data );
	$data = str_replace ( "C", "Ц", $data );
	$data = str_replace ( "R", "Р", $data );
	$data = str_replace ( "F", "Ф", $data );
	$data = str_replace ( "V", "В", $data );
	$data = str_replace ( "T", "Т", $data );
	$data = str_replace ( "G", "Г", $data );
	$data = str_replace ( "B", "Б", $data );
	$data = str_replace ( "Y", "Й", $data );
	$data = str_replace ( "H", "Х", $data );
	$data = str_replace ( "N", "Н", $data );
	$data = str_replace ( "U", "У", $data );
	$data = str_replace ( "J", "Ж", $data );
	$data = str_replace ( "M", "М", $data );
	$data = str_replace ( "I", "И", $data );
	$data = str_replace ( "K", "К", $data );
	$data = str_replace ( "O", "О", $data );
	$data = str_replace ( "L", "Л", $data );
	$data = str_replace ( "P", "П", $data );
	
	return $data;
}

function mailsend($subj, $to, $msg) {
	/**
	 * Обработка темы письма
	 */
	//$subject = $subj;
	$subject = '=?UTF-8?B?' . base64_encode($subj) . '?=';
	/**
	 * Заголовки
	 */
	$headers = array ();
	$headers ['mime'] = "MIME-Version: 1.0\r\n";
	$headers ['from'] = "From: \"=?UTF-8?B?" . base64_encode ( "Велес" ) . "?=\" <no_reply@{$_SERVER['HTTP_HOST']}>\r\n";
	$headers ['reply_to'] = "Reply-to: no_reply@{$_SERVER['HTTP_HOST']}\r\n";
	$headers ['content_type'] = "Content-Type: text/html; charset=utf-8\r\n";
	$headers ['content_transfer_encoding'] = "Content-Transfer-Encoding: 8bit\r\n";
	$headers ['priority'] = "X-Priority: 1 (Higuest)\n";
	$headers ['msmail-priority'] = "X-MSMail-Priority: High\n";
	$headers ['importance'] = "Importance: High\n";
	$headers ['return-path'] = "Return-Path: no_reply@{$_SERVER['HTTP_HOST']}\r\n";
	$headers ['x-mailer'] = "X-Mailer: PHP Mailer\n";
	$header = '';
	foreach ( $headers as $val )
		$header .= $val;
	
	/**
	 * Отправка письма
	 */
	mail ( $to, $subject, $msg, $header );
}

function wrapsum($summa) {
	$t = explode ( '.', $summa );
	if (isset ( $t [1] ))
		return str_replace ( " ", "&nbsp;", strrev ( wordwrap ( strrev ( $t [0] ), 3, ' ', 1 ) ) . "," . $t [1] );
	else
		return str_replace ( " ", "&nbsp;", strrev ( wordwrap ( strrev ( $t [0] ), 3, ' ', 1 ) ) );
}

?>