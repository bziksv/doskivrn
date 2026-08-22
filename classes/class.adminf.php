<?php

/**
 * Функции для администратора
 */

class adminf {
	
	/**
	 * Выводит листинг страниц
	 *
	 */
	public static function getListing($last, $current, $link) {
		if ($last == 1) {
			return '<span>страница:</span><a href="' . $link . '1" class="cur">1</a>';
		}
		$return = '<a href="';
		if ($current > 1) {
			$return .= '' . $link . ($current - 1) . '';
		} else {
			$return .= '#';
		}
		$return .= '" class="back">назад</a><span>страница:</span>';
		
		$start = $current - 2;
		$end = $current + 3;
		if ($start < 1) {
			$end += 2;
			$start = 1;
		}
		if ($end > $last) {
			$end = $last;
			$start = $last - 5;
		}
		$count = 0;
		for($i = $start; $i <= $end; $i ++) {
			if ($i < 1)
				continue;
			$count ++;
			if ($i != $start)
				$return .= '&nbsp;';
			$return .= '<a href="' . $link . $i . '"';
			if ($i == $current)
				$return .= ' class="cur"';
			$return .= '>' . $i . '</a>';
			if ($count == 6)
				break;
		}
		
		$return .= '<a href="';
		if ($current < $last) {
			$return .= '' . $link . ($current + 1) . '';
		} else {
			$return .= '#';
		}
		$return .= '" class="next">вперед</a><a href="' . $link . 'all" class="all">показать все</a>';
		
		/*if ($start > 1)
			$return = '<a href="' . $link . '1">1</a>&nbsp;...&nbsp;' . $return;
		if ($end < $last)
			$return .= '&nbsp;...&nbsp;<a href="' . $link . $last . '">' . $last . '</a>';*/
		return $return;
	}
	
	/**
	 * Создает список из шаблона
	 *
	 * @param array $names - массив имя => значение
	 * @param array $titles - заголовки для таблицы
	 */
	public static function set_tpl_list_1(array $names, array $titles, $listing = '') {
		global $smarty;
		
		$smarty->assign ( 'titles', $titles );
		$smarty->assign ( 'names', $names );
		if (! empty ( $listing )) {
			$smarty->assign ( 'listing', $listing );
		}
		
		return $smarty->fetch ( 'admin_list_1.tpl' );
	}
	public static function set_tpl_list_2(array $names, array $titles, $listing = '') {
		global $smarty;
		
		$smarty->assign ( 'titles', $titles );
		$smarty->assign ( 'names', $names );
		if (! empty ( $listing )) {
			$smarty->assign ( 'listing', $listing );
		}
		
		return $smarty->fetch ( 'admin_list_2.tpl' );
	}
	
	public static function set_date($time) {
		if ($time == 0) {
			return 'н/д';
		}
		return date ( "d.m.Y", $time );
	
	}

}

?>