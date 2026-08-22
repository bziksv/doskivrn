<?php
/**
 * Управление баннерами
 *
 */
class bannersctrl {
	
	static public function init($position_id) {
		global $dbc;
		$position_id = intval ( $position_id );
		if ($dbc->query ( "select count(id) from banners_positions where id='{$position_id}' and p_status='1'", 6 ) == 0)
			return;
			
		//************Баннеры********
		$obj = $dbc->query ( "select * from banners where p_id='{$position_id}' and b_status='1' order by b_stat_view ASC", 7 );
		if (! $obj) {
			return;
		}
		switch ($obj ["b_type"]) {
			case "0" :
				//Текст
				print nl2br ( $obj ["b_text"] );
				break;
			case "1" :
				//HTML
				print $obj ["b_text"];
				break;
			case "2" :
				//Картинка
				print '<a href="' . $obj ["b_link"] . '" target="_blank" title="">';
				print '<img src="' . $obj ["b_file_path"] . '" alt="" title=""';
				
				if ($obj ["b_width"] != '' && $obj ["b_width"] != '0')
					print ' width="' . $obj ["b_width"] . '"';
				if ($obj ["b_height"] != '' && $obj ["b_height"] != '0')
					print ' height="' . $obj ["b_height"] . '"';
				
				print '/></a>';
				break;
			case "3" :
				//Флеш
				print "<script language=\"javascript\">
					AC_FL_RunContent(
						'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab',
						'width', '{$obj["b_width"]}',
						'height', '{$obj["b_height"]}',
						'src', '{$obj["b_file_path"]}',
						'quality', 'high',
						'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
						'align', 'middle',
						'play', 'true',
						'loop', 'true',
						'scale', 'showall',
						'wmode', 'window',
						'devicefont', 'false',
						'id', 'banners_{$obj["id"]}',
						'bgcolor', '#ffffff',
						'name', 'banners_{$obj["id"]}',
						'menu', 'true',
						'allowFullScreen', 'false',
						'allowScriptAccess','sameDomain',
						'movie', '{$obj["b_file_path"]}',
						'salign', ''
						); //end banner code
				</script>";
				break;
			default :
				return;
		}
		$dbc->query ( "update banners set b_stat_view=b_stat_view+1 where id='{$obj["id"]}'" );
		return;
	}
}
?>