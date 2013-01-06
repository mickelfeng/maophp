<?php
@session_start();
final class up_mes{
        function __construct(){
           C(include WWW_CONFIG.'/model_config.php');
        }
        function up(){
                       $_POST["time"]=date("Y-m-d H:i:s",time());       
                       $mysql=M("blog");                                        
                       $res = $mysql->add();
                       $pri = $mysql->query("select blogid from blog where time='".$_POST["time"]."'");     
                       $blogid = $pri["0"]["blogid"];
                       $mysql_con=M("blogc");
                       $arr = array("blogid"=>$blogid,"content"=>$_POST["content"]);
                       $res_con = $mysql_con->add($arr);
                      if(!$res || !$res_con){
                        echo 0;
                      }else{
                         echo 1;
                      }
                 }
               }
?>