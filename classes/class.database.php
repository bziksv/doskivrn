<?

if (!defined('MODULE_SYS') || !MODULE_SYS) die("Файл может работать только как модуль!");

#Класс работы с базой данных
class database {
	private $db_name;
	private $db_host;
	private $db_user;
	private $db_password;
	public $dbh;
	private $sql;
	private $query;
	private $log = false;
	private $queryLog = array();
	private $countQuery = 0;
	
	public function setConnectValue($db_host,$db_name,$db_user,$db_password){
		$this->db_host = $db_host;
		$this->db_name = $db_name;
		$this->db_password = $db_password;
		$this->db_user = $db_user;
		$this->connect();
	}
	public function setLog($flag){
		$this->log = $flag;
	}
	
	function __destruct(){
	  if ($this->dbh)
	   mysql_close($this->dbh);
	}
#Коннект
	private function connect () {
		$this->dbh = @mysql_connect($this->db_host, $this->db_user, $this->db_password);
		if (!($this->dbh)) {
		  die("<div align='center' style='font-size:19px;margin-top:40px;'>Невозможно подключится к хосту: <b>$this->db_host</b></div>");
		}
		if (!mysql_select_db($this->db_name, $this->dbh)) {
		   die("<div align='center' style='font-size:19px;margin-top:40px;'>Невозможно подключится к БД: <b>$this->db_name</b></div>");
		}
		mysql_query("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'");
	}
#Дисконнект
	private function disconnect(){
		mysql_close($this->dbh);
	}
#SQL запрос
	public function query($sql,$type = 0){
		if (empty($sql)) return false;
		if (!$this->dbh) $this->connect();
		if($this->log)$time1=microtime();
		$query=mysql_query($sql,$this->dbh) or $this->print_error($sql,mysql_error());
		if($this->log)$time2=microtime();
		if ($query){
			if($this->log){$time=$time2-$time1;$this->setQueryInLog($sql,$time);}
			$this->sql = $sql;
			$this->query = $query;
			$this->countQuery ++;
			switch ($type){
				case 1:$res=$this->query;break;
				case 2:$res=$this->getObject();break;
				case 3:$res=$this->getArray();break;
				case 7:$res=mysql_fetch_assoc($query);break;
				case 4:$res=$this->getInsertId();break;
				case 5:$res=$this->getCountRSql();break;
				case 6:if ($this->getCountRSql()==0)$res=null;else$res=$this->getResult(0,0);break;
				default:$res=false;break;
			}
			return $res;
		}
		else{$this->query=null; return false;}
	}
#SQL запрос без буфферизации
	public function queryUnbuffer($sql,$type = 0){
		if (empty($sql)) return false;
		if (!$this->dbh) $this->connect();
		if($this->log)$time1=microtime();
		$query=mysql_unbuffered_query($sql,$this->dbh) or $this->print_error($sql,mysql_error());
		if($this->log)$time2=microtime();
		if ($query){
			if($this->log){$time=$time2-$time1;$this->setQueryInLog($sql,$time);}
			$this->sql = $sql;
			$this->query = $query;
			switch ($type){
				case 1:$res=$this->query;break;
				case 2:$res=$this->getObject();break;
				case 3:$res=$this->getArray();break;
				case 4:$res=$this->getInsertId();break;
				case 5:$res=$this->getCountRSql();break;
				case 6:if ($this->getCountRSql()==0)$res=null;else$res=$this->getResult(0,0);break;
				default:$res=false;break;
			}
			return $res;
		}
		else return false;
	}
#Вывод ошибки
	public function print_error($sql,$error){
		print '
		<table cellpadding="5" cellspacing="0" style="border:1px Solid #000000;">
		<tr>
			<td bgcolor="#faf859" height="20" align="center" style="border-bottom:1px Solid #000000;font-weight:bold;font-family:tahoma;font-size:9px;">ОШИБКА MYSQL!<br>Невозможно выполнить SQL запрос!</td>
		</tr>
		<tr>
			<td bgcolor="#FFFFFF" align="left" style="font-family:tahoma;font-size:10px;"><br><b>Запрос:</b>&nbsp;"'.$sql.'"
			<br><br>
			<b>Ошибка:</b>&nbsp;'.$error.'
			<br><br>Пожалуйста напишите о данной ошибке администрации!<br>Please mail for support about this error!
			</td>
		</tr>
		</table>
		';
		
	}
#Логирование запросов
	private function setQueryInLog($sql,$time){
		$cnt = count($this->queryLog);
		$this->queryLog[$cnt]["Sql"] = $sql;
		$this->queryLog[$cnt]["TimeEx"] = $time;
	}
#Вывод лога запросов
	public function getQueryLog(){
		foreach ($this->queryLog as $name => $value){
			print '<div>Запрос: '.$value["Sql"].'. Время выполнения: '.$value["TimeEx"].' сек.</div>';
		}
	}
#*******************Работа с последним запросом*******
#Возвращение ассоциативного массива
	public function getArray(){
		if ($this->query){
			return mysql_fetch_array($this->query);
		}
	}
	public function getArrayAs(){
	 if ($this->query){
			return mysql_fetch_assoc($this->query);
		}
	}
#Возвращение объекта
	public function getObject(){
		if ($this->query){
			return mysql_fetch_object($this->query);
		}
	}
	public function getObjectExt($query){
		if ($query){
			return mysql_fetch_object($query);
		}
	}
#Возвращение неассоциативного массива
	public function getArrayN(){
		if ($this->query){
			return mysql_fetch_row($this->query);
		}
	}
#Кол-во полей результата запроса
	public function getCountFSql(){
		if ($this->query){
			return mysql_num_fields($this->query);
		}
	}
#Кол-во рядов результата запроса
	public function getCountRSql(){
		if ($this->query){
			return mysql_num_rows($this->query);
		}
	}
#Возвращение ID при последнем SQL INSERT запросе
	public function getInsertId(){
		if ($this->query){
			return mysql_insert_id($this->dbh);
		}
	}
#Возвращение результат запроса
	public function getResult($row,$field){
		if ($this->query and $this->getCountRSql()!=0){
			return mysql_result($this->query,$row,$field);
		}
	}
	#
  public function getAllQuery(){
    return $this->query;
  }
  
  public function getCountQuery(){
    return $this->countQuery;
  }
}
?>