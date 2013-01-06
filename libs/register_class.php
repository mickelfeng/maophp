<?php
 class register{              
        public function __construct(){

       }       
        static function register(){
            $mysql=M("users");               
            $affact=$mysql->exe("insert into users (name,passwd) values('".$_POST['name']."','".md5($_POST['passwd'])."')");
            if($affact!==1){
 ?>
                   <script type="text/javascript">
                     alert "注册失败";
                     location.href="#";
                   </script>

<?php
            }else{
            $idarr = $mysql->query("select id from users where name ='".$_POST['name']."'");
           

            @session_start();
            setcookie("id", $idarr["0"]["id"]);
            setcookie("name", $_POST['name']);
            $_SESSION["id"]=$idarr["0"]["id"];
            $_SESSION["name"]=$_POST['name'];            
            $url = __WEB__."?c=ucenter&id=".$idarr['0']['id'];
            echo "<script>alert('恭喜你注册成功！')</script>";
?>
        <script type="text/javascript">
          location.href="<?php echo $url;?>"; 
        </script>
<?php
        }
       }
   } 
 
?>