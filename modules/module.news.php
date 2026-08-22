<?
if (! MODULE_SYS)
	die ( "Файл может работать только как модуль!" );

function news_main() {
	global $dbc;
	$GLOBALS ['tpl'] = 'news.tpl';
	if (! isset ( $_GET ["page"] ))
		$_GET ["page"] = '';
	
	$res1 = $dbc->query ( "select * from NewsCategorys where catParent='0' order by catName ASC", 1 );
	$cats = array ();
	for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
		$cats [] = mysql_fetch_assoc ( $res1 );
	}
	$GLOBALS ['smarty']->assign ( "cats_list", $cats );
	switch ($_GET ["page"]) {
		case "view" :
			return news_view ();
			break;
		default :
			return news_cats ();
			break;
	}
	return 'Вероятно вы ошиблись при вводе адреса.';
}
function news_last() {
	global $dbc;
	$count = 5;
	
	$limit = 6;
	$curPage = 1;
	if (isset ( $_GET ["page"] ) && is_numeric ( $_GET ["page"] ) && $_GET ["page"] > 0) {
		$begin = ($_GET ["page"] - 1) * $limit;
		$curPage = $_GET ["page"];
	} else
		$begin = 0;
	
	$countAll = $dbc->query ( "select count(id) from News", 6 );
	$res1 = $dbc->query ( " SELECT News.*, catName, newsCat, catTranslit
      FROM News Left Join NewsCategorys on News.newsCat=NewsCategorys.id ORDER by dataNews DESC LIMIT $begin,$limit ", 1 );
	$news = '';
	for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
		$objNews = mysql_fetch_object ( $res1 );
		$objNews->dataNews = getFormatData2 ( $objNews->dataNews );
		$objNews->newsCat = $objNews->catTranslit;
		$objNews->newsContent = trim ( strip_tags ( str_replace ( "&nbsp;", "", $objNews->newsContent ) ) );
		if (($i + 1) == mysql_num_rows ( $res1 ))
			$GLOBALS ['smarty']->assign ( "last", '1' );
		
		if ($objNews->file != '') {
			$size = getimagesize ( $_SERVER ['DOCUMENT_ROOT'] . "/files/news/" . $objNews->file );
			$objNews->sizes = array ("width" => $size [0], "height" => $size [1] );
		}
		
		$GLOBALS ['smarty']->assign ( "news", $objNews );
		$news .= $GLOBALS ['smarty']->fetch ( 'news_newsitem.tpl' );
	}
	$maxPages = floor ( $countAll / $limit );
	if (($countAll % $limit) > 0)
		$maxPages ++;
	
	$GLOBALS ["smarty"]->assign ( 'listing', getListing ( $maxPages, $curPage, "/news/" ) );
	$GLOBALS ["smarty"]->assign ( 'content', $news );
	return $GLOBALS ["smarty"]->fetch ( 'news_list.tpl' );
}
function getRealPath($id, $fl = true) {
	$center = '';
	$arrayCat = getStructureCat ( $id );
	foreach ( $arrayCat as $name => $value )
		$center .= getStructureView ( $value, '', $fl );
	return $center;
}

//Создает структуру каталога
function getStructureCat($id = 0) {
	global $dbc;
	$str = array ();
	$res1 = $dbc->query ( "SELECT id, catName as name, catParent
  FROM NewsCategorys 
  WHERE id = '$id'", 1 );
	for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
		$tmp = array (mysql_result ( $res1, $i, "name" ), mysql_result ( $res1, $i, "id" ) );
		$tmp2 = getStructureCat ( mysql_result ( $res1, $i, "catParent" ) );
		$str [] = array ($tmp, $tmp2 );
	}
	return $str;
}

//Создает структуру формы
function getStructureView($array = false, $padding = '', $fl = false) {
	if ($fl) {
		$return = '' . $array [0] [0] . '';
	} else
		$return = '' . $array [0] [0] . '';
	if (isset ( $array [1] ) && count ( $array [1] ) > 0)
		foreach ( $array [1] as $name => $value )
			$return = getStructureView ( $value, $padding ) . ' :: ' . $return;
	return $return;
}
//Просмотр категории
function news_cats() {
	global $dbc;
	$GLOBALS ['smarty']->assign ( 'pagename', "Новости" );
	//************************Заголовок страницы********************************
	$GLOBALS ['title'] = "Новости";
	$GLOBALS ['description'] = "";
	$GLOBALS ['keywords'] = "";
	//**************************************************************************
	

	if (! isset ( $_GET ["page"] ) || ! preg_match ( "/^[a-zA-Z0-9\-]{1,150}$/", $_GET ["page"] ))
		return news_last ();
	$cat = $dbc->query ( "select * from NewsCategorys where catTranslit='{$_GET["page"]}'", 2 );
	if (! $cat)
		return news_last ();
	$path = getRealPath ( $cat->id );
	$GLOBALS ['smarty']->assign ( 'pagename', "Новости &raquo; " . $path );
	$news = '';
	$limit = 6;
	$curPage = 1;
	if (isset ( $_GET ["action"] ) && is_numeric ( $_GET ["action"] ) && $_GET ["action"] > 0) {
		$begin = ($_GET ["action"] - 1) * $limit;
		$curPage = $_GET ["action"];
	} else
		$begin = 0;
	$countAll = $dbc->query ( "select count(id) from News where newsCat='{$cat->id}'", 6 );
	$res1 = $dbc->query ( "select * from News where newsCat='{$cat->id}' order by id DESC limit $begin,$limit", 1 );
	for($i = 0; $i < mysql_num_rows ( $res1 ); $i ++) {
		$obj = mysql_fetch_object ( $res1 );
		$obj->dataNews = getFormatData2 ( $obj->dataNews );
		$obj->newsContent = trim ( strip_tags ( str_replace ( "&nbsp;", "", $obj->newsContent ) ) );
		if ($objNews->file != '') {
			$size = getimagesize ( $_SERVER ['DOCUMENT_ROOT'] . "/files/news/" . $objNews->file );
			$objNews->sizes = array ("width" => $size [0], "height" => $size [1] );
		}
		$GLOBALS ['smarty']->assign ( "news", $obj );
		$news .= $GLOBALS ['smarty']->fetch ( 'news_newsitem.tpl' );
	}
	
	$maxPages = floor ( $countAll / $limit );
	if (($countAll % $limit) > 0)
		$maxPages ++;
	
	$GLOBALS ["smarty"]->assign ( "listing", getListing ( $maxPages, $curPage, "/news/{$cat->catTranslit}/" ) );
	$GLOBALS ["smarty"]->assign ( "cat", $cat );
	$GLOBALS ["smarty"]->assign ( 'content', $news );
	return $GLOBALS ["smarty"]->fetch ( 'news_list.tpl' );

}
//Просмотр новости
function news_view() {
	global $dbc;
	
	if (! isset ( $_GET ["action"] ) || ! is_numeric ( $_GET ["action"] ))
		return printErrors::show ( 48 );
	
	$objNews = $dbc->query ( " SELECT News.*, catName, newsCat, catTranslit
      FROM News Left Join NewsCategorys on News.newsCat=NewsCategorys.id WHERE News.id = '{$_GET["action"]}'
        ORDER by dataNews DESC
        LIMIT 0 , 1", 2 );
	$objNews->dataNews = getFormatData2 ( $objNews->dataNews );
	$GLOBALS ['smarty']->assign ( "news", $objNews );
	$path = getRealPath ( $objNews->newsCat, false );
	$GLOBALS ['smarty']->assign ( 'pagename', $objNews->newsTitle );
	
	if ($objNews->file != '') {
		$size = getimagesize ( $_SERVER ['DOCUMENT_ROOT'] . "/files/news/" . $objNews->file );
		$objNews->sizes = array ("width" => $size [0], "height" => $size [1] );
	}
	
	$GLOBALS ['title'] = $objNews->newsPageTitle;
	$GLOBALS ['description'] = $objNews->newsPageDesc;
	$GLOBALS ['keywords'] = $objNews->newsPageKeywords;
	
	$GLOBALS ["smarty"]->assign ( 'path', "<a href=\"/news/\">Новости</a> :: " . $path );
	return $GLOBALS ['smarty']->fetch ( 'news_view.tpl' );
}
?>