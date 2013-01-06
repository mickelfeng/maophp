<?php
   class upload{
      protected $uploadDir;
      protected $allowSize;
      protected $allowType;
      protected $dbFile;
      protected $up_type;
      protected $word_error;
      protected $file_name;
      protected $file_size;

    public function messge_error(){
      $mes=$this->word_error;
      $stat = $this->up_type;
      $size = round($this->file_size/1024/1024,2);
      return array("mes"=>$mes,"stat"=>$stat,"size"=>$size);
      
    }
    public function __construct(){
      C(include WWW_CONFIG.'/upload_config.php');
      is_dir(C("upload","upload_dir"))||mkdir(C("upload","upload_dir"),0777,true);
      is_dir(dirname(C("upload","upload_db_dir")))||mkdir(dirname(C("upload","upload_db_dir")),0777,true);
      	$this->uploadDir=C("upload","upload_dir");
      	$this->allowSize=C("upload","upload_allow_size");
      	$this->allowType=C("upload","upload_allow_type");        
      	$this->dbFile=C("upload","upload_db_dir");
      }
    public function uploads(){
      if(empty($_FILES)){
      	return;
      }
      $files = $this->format();
      $files = $this->check($files);
      $db = $this->move_file($files);      
      file_exists($this->dbFile) || file_put_contents($this->dbFile, "<?php\n return array(\n);\n?>");
      $old = include ($this->dbFile);
      $dbs = array_merge($db,$old);
      file_put_contents($this->dbFile, "<?php\n return ".var_export($dbs,true).";\n?>");
     }

    
    private function format(){
    	$arr = array();
    	$id=0;
    	foreach ($_FILES as $key => $value) {
    		if (is_array($value["name"])) {
    			foreach ($value["name"] as $k => $v) {    				
    				$arr[$id]["name"]=$value["name"][$k];
    				$arr[$id]["type"]=$value["type"][$k];
    				$arr[$id]["tmp_name"]=$value["tmp_name"][$k];
    				$arr[$id]["error"]=$value["error"][$k];
    				$arr[$id]["size"]=$value["size"][$k];    				   
    				$id++;
    			}

    		}else{
    			$arr[$id]=$value;
    			$id++;
    		}
    	}
    	return $arr;
    }

    private function check($files){
         $arr = array();
         foreach ($files as $file) {
         	//过率有错误的文件
           if($file["error"]>0){
            $this->word_error="上传的文件不符合要求!";
            $this->up_type="error";
             continue;
           }
           if($file["size"]>$this->allowSize){
            $this->word_error="上传的文件大小不符合要求!";
            $this->up_type="error";
           	 continue;
           }
           $info = pathinfo($file['name']);
           if(!in_array($info['extension'],$this->allowType)){
            $this->word_error="上传的文件类型不符合要求!";
            $this->up_type="error";
           	 continue;
           }
           if(!is_uploaded_file($file['tmp_name'])) {
            $this->word_error="上传文件的临时文件无法找到!";
            $this->up_type="error";
           	 continue;
           }
           $file['extension'] = $info['extension'];
		   $file['time']=time();
		   $arr[]=$file;
         }
         return $arr;

    }

    private function move_file($files){
        #创建上传目录
		is_dir($this->uploadDir) || mkdir($this->uploadDir,0777,true);
		$arr=array();
		foreach($files as $file){
				$toFile = $this->uploadDir.'/'.time().mt_rand(0,1000).'.'.$file['extension'];
				if(move_uploaded_file($file['tmp_name'], $toFile)){          
          $this->file_name = $toFile;
          $this->file_size = $file["size"];
          $this->up_type =1;    
          $this->word_error=substr($this->file_name,1);
					unset($file["tmp_name"]);
					$db_file = $file;
					$arr[]= $db_file;
				}
			}
		return $arr;

    }
   
 

   }
?>