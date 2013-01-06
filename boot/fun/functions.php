<?php
/*--------------------------------------------------------------------------------------------------------
    类的自动加载
  --------------------------------------------------------------------------------------------------------*/
  function __autoload($class_name){
  	if(strlen($class_name)>7 && substr($class_name, -7)=='control'){
    $class_file = APP_NAME.'/control/'.$class_name.'.php';    
  	}else{
  	$class_file = MAO_ROOT.'/libs/'.$class_name."_class.php";
  }
    if(!is_file($class_file)){
    	error($class_file.' is not exit!');
    	return;
    }
  	include $class_file;
  }
  /*--------------------------------------------------------------------------------------------------------
    创建数据库对象
  --------------------------------------------------------------------------------------------------------*/
  function M($table=null){
      if(is_null($table)){
        error("请选择表");
      }
      return new model($table);
    }

  /*--------------------------------------------------------------------------------------------------------
    打印处理函数
  --------------------------------------------------------------------------------------------------------*/
  function p($mes){
  	echo "<pre>";
  	print_r($mes);
  }

  /*--------------------------------------------------------------------------------------------------------
    序列化处理函数
  --------------------------------------------------------------------------------------------------------*/
   function S($data=null,$cache_file=null){
     $cache_file = is_null($cache_file)?WWW_TMP.'/'.APP_NAME.'/'.CONTROL.'/cache/'.md5(serialize($data)).'.php':$cache_file;
     if(!$data && !is_file($cache_file)){
      return;
     }
     if(!$data && is_file($cache_file)){
       return unserialize(file_get_contents($cache_file));
     }else{
      $dirname = dirname($cache_file);
      is_dir($dirname) || mkdir($dirname,0777,true);
     }
    $data = serialize($data);
    file_put_contents($cache_file, $data);
   }

  /*--------------------------------------------------------------------------------------------------------------
  1，0个参数无执行动作
  2，传一个参数，必须为一个数组（配置文件）|———— 作用加载数组。
  3，传两个参数，第一个参数必须为字符串（配置文件类型）；第二个参数必须也为字符串（属性名）|————作用是获取某一功能配置文件的其中一个属性值。
  4，传三个参数，前两个参数和3中的相同，后一个参数为字符串|----作用时临时的设置某一功能配置文件的其中一个属性值。
  5，传四个参数，最后一个时布尔值，为true是将4中设置的属性值写入配置文件，false时用于临时的设置
  ---------------------------------------------------------------------------------------------------------------*/
  function C($type=null,$name=null,$value=null,$flag=false){  	
  	if(is_null($type)){
         return ;
  	}    
    if(is_string($type)){
      $config_file = WWW_CONFIG.'/'.$type.'_config.php';
      if(!is_file($config_file)){
        error($config_file."配置文件无法找到");
      }
       $config = $type."_config";
       $$config = empty($$config)?array():$$config;

          if(!is_null($name)){
              	if(is_string($name)){
                         $$config = count($$config)==0?array_merge(include $config_file):$$config;                                               
                      		if(is_null($value)){
                           // p($$config);echo "<br/>";
                            //echo $name."<br/>";
                         
                           eval('$a = $'.$config.'[$name];');                                            
                      			 return isset($a)?$a:false;                          		
                      		}else if(!is_null($value)){
                                    if($flag==false){                                     
                      		               $$config[$name]=$value;
                                   }else{
                                      $conf = include $config_file;
                                      $conf[$name]=$value;
                                      file_put_contents($config_file, "<?php \n if(!defined(\"WWW_CONFIG\"))exit;\n return  ".var_export($conf,true)."\n?>");
                                   }
                                }
              	}
              	
             }else{
                 $$config = count($$config)==0?array_merge(include $config_file):$$config; 
             }

  }else if(is_array($type)){
        $config = $type['type'];
        $$config = empty($$config)?array():$$config;
        $$config=count($$config)==0?array_merge($type):$$config;
  }else{
        error("C参数的类型有误");
     }
  }

/*--------------------------------------------------------------------------------------------------------
    错误信息的处理
  --------------------------------------------------------------------------------------------------------*/
  function error($mes){
		echo '<div style="border:solid 1px #eee;padding:29px;">';
		echo $mes;
		echo "</div>";
		exit;
	}
  
   





?>