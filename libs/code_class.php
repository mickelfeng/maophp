<?php
@session_start();
final class code{
        function __construct(){
           C(include WWW_CONFIG.'/code_config.php');
        }
        function show(){
                      $code = C("code","code");
                      $len = C("code","code_nums");
                      $code_str='';                     
                      for ($i=0; $i < $len; $i++) {
                      $n = mt_rand(0,strlen($code)-1);
                      $code_str.=strtoupper($code[$n]);
                      }
                      $_SESSION['code']=$code_str;
                      $img = imagecreatetruecolor(C("code","code_width"), C("code","code_height"));     
                      $color = imagecolorallocate($img, 0,0,255);
                      $color2 = imagecolorallocate($img, 255,255,255);
                      imagefill($img, 0,0, $color2);
                      imagestring($img,C("code","code_size"),C("code","code_x"),C("code","code_y"), $code_str, $color);
                      header("Content-type:image/png");
                      imagepng($img);
                 }
               }
?>