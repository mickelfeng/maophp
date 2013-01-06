<?php
     class index_control extends public_class{
				function __construct(){
					header("Content-type:text/html;charset=utf-8");
				}
				function index(){
					echo "<div style='border:solid 3px #f00;text-align:center;padding:30px;'>
						欢迎使用MAO框架产品
					</div>";
				}
	}
?>