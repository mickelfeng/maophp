<?php
   class login{
        public function __construct(){

       }
        static function login(){
            $name = $_POST["name"];
            $passwd = md5($_POST["passwd"]);
            $mysql=M("users");
            $u_mes = $mysql->where("name = '".$name."'")->query();
            if($u_mes['0']['name']==$name && $u_mes['0']['passwd']==$passwd){
            	 @session_start();
                 setcookie("id", $u_mes['0']['id']);
                 setcookie("name", $u_mes['0']['name']);
                 $_SESSION["id"]=$u_mes['0']['id'];
                 $_SESSION["name"]=$u_mes['0']['name'];
                 $ur1 = __WEB__."?c=ucenter&id=".$u_mes['0']['id'];                 
                echo "<script>alert('恭喜你登陆成功！')</script>";
                echo "<script>location.href='".$ur1."'</script>";
            	
            }else{
            	echo "<script>alert('登陆失败!');history.go(-1)</script>";
                        	
            }
        }       
   }

?>