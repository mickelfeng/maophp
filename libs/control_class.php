<?php
    /*------------------------------------------------------
     顶级控制类，实现控制类中的共用的方法
   ------------------------------------------------------*/
   class control{
       static protected $view;//视图对象
       static protected $login;//注册登录对象
       private function get_view_obj(){
       	if(is_null(self::$view)){
       		self::$view=new view();
       	}
       }
     
       public function login(){             
             login::login();
       }
       public function register(){           
             register::register();
       }

       public function assign ($name,$value){
              self::get_view_obj();
              self::$view->assign($name,$value);
       }
       public function display($tpl,$cache_time){
         self::get_view_obj();      
       	 $tpl=$tpl?$tpl:ACTION;         
       	 self::$view->display($tpl,$cache_time);
         
       }
       public function is_cached($tpl=null,$cacheTime=null){
    			$tpl = is_null($tpl)?ACTION.C("tpl","tpl_fix"):$tpl;
    			self::get_view_obj();
    			self::$view->smarty->caching = true;
    			self::$view->smarty->cache_lifetime=$cacheTime?$cacheTime:C("tpl","cache_lifetime");     
    			return self::$view->smarty->is_cached($tpl,$_SERVER['REQUEST_URI']);
		}


   }
?>