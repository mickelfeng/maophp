<?php   
   class model{
   	static private $db;
   	private $opt = array(
        "where"=>"",
        "limit"=>"",
        "table"=>"",
        "group"=>"",
        "order"=>"",
        "having"=>"",
        "pri"=>"",
        "fields"=>"",
   		);
    private $opt_arr = array("where","group","having","order","limit");
    public function __construct($table_name){     
        $this->db_connect();
     	$this->opt["table"]=$table_name;
        $this->set_field_cache();  	
    }
    public function db_connect($db_name=null){
        C(include WWW_CONFIG.'/model_config.php');
        $select_db = is_null($db_name)?C("model","db_name"):$db_name;
        if(is_null(self::$db)){
            $link = @mysql_connect(C("model","db_host"),C("model","db_user"),C("model","db_password"));
            if (!$link) {
                            die('Could not connect: ' . mysql_error());
                        }
            mysql_select_db($select_db);
            mysql_query("set names'".C("model","db_char")."'");
        }
    }
    public function exe($sql){
        mysql_query($sql);
        return mysql_affected_rows();
    }
    public function add($data=null){
        if(is_null($data)){
            $data = empty($_POST)?null:$_POST;
        }
        $fields = '';
        $values = '';        
        foreach ($data as $name => $value) {                
            if (!in_array($name, $this->opt["fields"])) {
                continue;
            }
            $fields.=$name.',';
            $values.="'".$value."',";
        }        
        $fields = substr($fields, 0,-1);
        $values = substr($values, 0,-1);
        $sql = "insert into ".$this->opt['table']." (".$fields.") values(".$values.")";
        return $this->exe($sql);
    }
    public function del($sql=null){
       if(is_null($sql)){
          if (!$this->opt['where']) {
              error("删除必须要有条件");
          }
       }       
       if(is_numeric($sql)){
            $this->opt["where"]=" where ".$this->opt['pri']."=".$sql;
        }else{
            $this->opt["where"]=" where ".$sql;
        }
        $sql = "delete from ".$this->opt["table"].$this->opt["where"].$this->opt["order"].$this->opt["limit"];
       return $this->exe($sql);
    }
    public function count($sql=null){        
        $sql = $sql?$sql:"select * from ".$this->opt['table'].$this->opt["where"];      
        $data = $this->query($sql);
        return $data?count($data):0;
    }    
    public function update($data = null){
        if(is_null($data)){
            $data = !empty($_POST)?$_POST:null;
            if (!$data) {
                return;
            }
        }
        if (is_array($data)) {
            if (array_key_exists($this->opt['pri'], $data)) {
               $this->opt['where']=" where ".$this->opt["pri"]."=".$data[$this->opt["pri"]]; 
            }
            $sql = "update ".$this->opt["table"]." set ";
            foreach ($data as $name => $value) {
                if (!in_array($name, $this->opt["fields"])) {
                    continue;
                }
                $sql.=$name."='".$value."',";
            }
            $sql = substr($sql,0,-1).$this->opt["where"];
        }else{
            $sql = $data;
        }
        return $this->exe($sql);        
    }
    private function set_field_cache(){
        $cache_file = WWW_TMP.'/'.APP_NAME.'/'.CONTROL.'/cache/'.md5($this->opt["table"]).'.php';
        $data = S("",$cache_file);
        $tun_cache = C("model","tun_cache");
        if($data && $tun_cache){
            $this->opt["fields"]=$data["fields"];
            $this->opt["pri"]=$data["pri"];
            return;
        }
        $table = $this->opt['table'];
        $fields = $this->query("desc ".$table);
        $field_arr=array();
        $field_arr['pri']='';
        $field_arr['fields']=array();
        foreach ($fields as $field) {
            if ($field["Key"]=="PRI") {
                $field_arr["pri"]=$field["Field"];
            }
            $field_arr["fields"][]=$field["Field"];
        }
        S($field_arr,$cache_file);
        $this->opt = array_merge($this->opt,$field_arr);
    }
    public function query($sql=null){       
        if(is_null($sql)){
            $sql = "select * from ".$this->opt["table"].$this->opt['where'].$this->opt["group"].$this->opt["having"].$this->opt["order"].$this->opt["limit"];
        }       
        foreach ($this->opt_arr as $value) {
           if(!preg_match('/'.$value.'/i',$sql)){
             $sql.=$this->opt[$value];
           }
        }       
        $result = mysql_query($sql);
        if($result){
            $rows =array();
            while ($row = mysql_fetch_assoc($result)) {
                $rows[]=$row;
            }
            return $rows;
        }else{

        }
    }
    public function where($arg=null){
    	if(is_null($arg)){
    		return $this;
    	}
    	$this->opt["where"]=" WHERE ".$arg;        
        return $this;
    }
    public function limit($arg=null){
    	if(is_null($arg)){
    		return $this;
    	}
    	$this->opt["limit"]=" LIMIT ".$arg;
        return $this;
    }
    public function order($arg=null){
    if(is_null($arg)){
            return $this;
        }
    $this->opt["order"]=" order by ".$arg;
    return $this;
     }
   }
   
?>