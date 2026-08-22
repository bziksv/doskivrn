<?

if (!defined('MODULE_SYS') || !MODULE_SYS) die("Файл может работать только как модуль!");

include $path_conf."smarty/Smarty.class.php";

class template_main extends Smarty{
		
	function __construct($template){
		global $path_templates,$global_path;
		$this->Smarty();
		$this->compile_check = true;
		//$this->debugging = true;
		$this->template_dir=$path_templates.$template.'/';
		$this->compile_dir=$global_path.'templates_c/';
		$this->config_dir=$global_path.'configs/';
		$this->cache_dir=$global_path.'cache/';
		//$this->caching=true;
	}
	
}

class template_admin extends Smarty{
		
	function __construct($template){
		$this->template_admin($template);
	}

	function template_admin($template){
		global $path_templates,$global_path;
		$this->Smarty();
		$this->compile_check = true;
		//$this->debugging = true;
		$this->template_dir=$path_templates.$template.'/';
		$this->compile_dir=$global_path.'templates_c/';
		$this->config_dir=$global_path.'configs/';
		$this->cache_dir=$global_path.'cache/';
		//$this->caching=true;
	}
	
}
?>