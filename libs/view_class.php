<?php
  /*------------------------------------------------------
     顶级控制类实现的视图方法类
   ------------------------------------------------------*/
   include MAO_ROOT.'/org/smarty/Smarty.class.php';
   class view{
   	public $smarty;//smarty对象
    public function __construct(){  
      C(include WWW_CONFIG.'/tpl_config.php');  
    	$this->smarty=new Smarty();
    	$tpl_dir=C("tpl","tpl_dir")?C("tpl","tpl_dir"):WWW_TPL;
    	$this->smarty->template_dir=$tpl_dir;
    	$compile_dir=WWW_TMP.'/'.APP_NAME.'/'.CONTROL.'/'.ACTION.'/compile';//编译目录
    	is_dir($compile_dir) || mkdir($compile_dir,0777,true);
    	$this->smarty->compile_dir =$compile_dir;
    	$this->smarty->left_delimiter =C("tpl","left_delimiter");
		$this->smarty->right_delimiter =C("tpl","right_delimiter");
		$cache_dir = WWW_TMP.'/'.APP_NAME.'/'.CONTROL.'/'.ACTION.'/cache';//缓存目录
		is_dir($cache_dir) || mkdir($cache_dir,0777,true);
		$this->smarty->cache_dir = $cache_dir;
    }
    public function assign($name,$value){
    	$this->smarty->assign($name,$value);
    }
    public function display($tpl,$cache_time){      
    	$tpl=str_replace(C("tpl","tpl_fix"),"", $tpl).C("tpl","tpl_fix");
    	if(!is_file($this->smarty->template_dir.'/'.$tpl)){
    		error("模板文件".$this->smarty->template_dir.'/'.$tpl."不存在");
    	}
    	if (is_null($cache_time)) {
    		$this->smarty->caching = C("tpl","caching");
    		$this->smarty->cache_lifetime = C("tpl","cache_lifetime");
    	}else if($cache_time<=0){
          $this->smarty->cacheing = false;
    	}else if($cache_time>0){
    		$this->smarty->caching = true;
    		$this->smarty->cache_lifetime = $cache_time;
    	}    
    	$this->smarty->display($tpl,$_SERVER['REQUEST_URI']);
    }

   }
?>