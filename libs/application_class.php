<?php
    /*-----------------------------------------------------------
         项目start的进一步准备 1设置url常量;2加载配置项;3创建控制器;4运行
       ----------------------------------------------------------*/
final class application{   	 
      static public function run(){
        self::set_url_const();//设置url常量;        
        self::copy_config();//配置项初始化;
        self::create_control_demo();//创建控制器
        self::run_app();//运行
      }
      private function run_app(){
          $control = CONTROL.'_control';
          $action=ACTION;                  
          $obj=new $control;
          $obj->$action();
      }
      private function set_url_const(){
      	$control = isset($_GET['c'])?$_GET['c']:"index";
      	$action = isset($_GET['a'])?$_GET['a']:"index";
      	define("CONTROL", $control);
      	define("ACTION", $action);      	
      	$root = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']);
      	define("__ROOT__", $root);
        define("__WEB__", "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
      	define("__TPL__", $root.'/'.APP_NAME.'/tpl');//网站模板的url
      	define("__PUBLIC__",$root.'/'.APP_NAME.'/tpl/public');#:模板URL
      	$control = __ROOT__.'/'.basename($_SERVER['SCRIPT_NAME']).'?c='.CONTROL;
      	define("__CONTROL__", $control);
      	define("__ACTION__", $control.'$a='.ACTION);

      }
      private function copy_config(){
        $conf_arr = glob(MAO_ROOT.'/boot/config/*');    
        foreach ($conf_arr as $conf) {       
          if(!is_file(WWW_CONFIG.'/'.basename($conf))||filemtime($conf)>filemtime(WWW_CONFIG.'/'.basename($conf))){
          copy($conf,WWW_CONFIG.'/'.basename($conf));
        }
          
        }     
      }
      function create_control_demo(){
      	  $demo_file = WWW_CONTROL.'/index_control.php';
      	  if(is_file($demo_file))return;
      	  $data = file_get_contents(MAO_ROOT.'/boot/welcome.php');
          file_put_contents($demo_file, $data);                 
      }
   }
?>