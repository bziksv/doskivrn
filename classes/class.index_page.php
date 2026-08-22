<?php

class index_page {
	
	static public function get_index() {
		global $dbc;
		/**
		 * Новости на главную
		 */
		
		$res1 = $dbc->query ( " SELECT newsPreview as `desc`, dataNews as data, id, newsTitle as `title` FROM News ORDER by dataNews DESC LIMIT 0,4 ", 1 );
		$news = array ();
		for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
			$news [$i] = mysql_fetch_array ( $res1 );
			$news [$i] ["data"] = getFormatData2 ( $news [$i] ["data"] );
		}
		$GLOBALS ['smarty']->assign ( "news_index", $news );
	
	}

}

?>