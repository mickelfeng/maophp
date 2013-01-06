<?php
   /*----------------------------------------------------------------------------------------
     项目初始化&配置加载,1定义路径常量 2生成基本的运行文件夹 3加载系统的函数库 4创建并运行编译文件 5运行项目
   ------------------------------------------------------------------------------------------*/
   class start{
     private function __construct(){

     }
     static public function run(){
      $compile_boot = defined("COMPILE")?COMPILE:true;
      self::set_url_const();//定义路径常量
      self::create_base_dir();//生成基本的运行文件夹
     if(self::check_compile() && $compile_boot){
            include WWW_TMP.'/compile_tmp/compile.php';
     }else{        
        self::load_sys_fun();//加载系统的函数库
        self::create_compile();//创建并运行编译文件
      }
        application::run();//运行项目

     }
     private function check_compile(){
       return is_file(WWW_TMP.'/compile_tmp/compile.php');
     }
     private function create_compile(){
       $compile_file=WWW_TMP.'/compile_tmp/compile.php';
       is_dir(dirname($compile_file))||mkdir(dirname($compile_file),0777,true);
       $files = include MAO_ROOT.'/boot/compile_tmp/compile_file.php';
       $data ='';
       foreach ($files as $file) {
         $file = trim(file_get_contents($file));
         $file = substr($file, 5,-2);
         $data.=$file;
       }
       $data = self::preg($data);
       file_put_contents($compile_file, "<?php \n ".$data."\n?>");    
     }
     private function preg ($data){
          $preg = '/(?:(?<!http:)\/\/.*)|(?:#:.*)/';
          $data = preg_replace($preg,"",$data);
          $preg1 = '/(?<=\s)\/\*.*\*\//sU';
          $data = preg_replace($preg1,"",$data);
          $preg2 = '/(^\s*)|(\s*$)|\n/m';
          $data = preg_replace($preg2,"",$data);
         return $data;
     }
     private function load_sys_fun(){
     	$sys_fun = array(MAO_ROOT.'/boot/fun/functions.php');  //|-------改造成函数文件的遍历---------|
     	foreach ($sys_fun as $fun) {
     		include $fun;
     	}     
     }
     private function set_url_const(){
       $mao_php =dirname(__FILE__);
       $WWW_ROOT = dirname($mao_php);
       define("MAO_ROOT",$mao_php);//框架的根目录
       define("MAO_CONFIG", MAO_ROOT.'/boot/config');
       define("WWW_ROOT",$WWW_ROOT);//网站的根目录
       define("WWW_TMP", WWW_ROOT.'/tmp');//网站的临时文件夹
       define("WWW_APP", WWW_ROOT.'/'.APP_NAME);//网站应用的目录
       define("WWW_CONTROL",WWW_APP.'/control');//网站控制器的目录
       define("WWW_TPL",WWW_APP.'/tpl');//网站模板的目录
       define("WWW_CONFIG", WWW_APP.'/config');//网站的配置文件的目录
       define("WWW_LIBS", WWW_APP.'/libs');//网站的公共的类库
 
     }
     private function create_base_dir(){
     	$base_dir_arr = array(WWW_TMP,WWW_APP,WWW_CONTROL,WWW_TPL,WWW_CONFIG,WWW_LIBS);
     	foreach ($base_dir_arr as $dir) {
     		is_dir($dir)||mkdir($dir,0777,true);
     	}
     }

   }
 
    start::run();
?>