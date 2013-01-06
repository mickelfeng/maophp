<?php
   /*------------------------------------------------------
     共用控制类
   ------------------------------------------------------*/
    class public_class {
                public function __construct(){
                	date_default_timezone_set('PRC');
                }       
				public function index(){
					if(!control::is_cached()){
					   control::assign("yu","mao123");
				     }
					control::display();
				}
				public function up_mes(){
					up_mes::up();
				}				
				public function code(){					
					code::show();
				}
				public function assgin($name,$value){					
					control::assign($name,$value);
				}
				public function display($tpl=null,$cache_time=null){				
					control::display($tpl,$cache_time);					 
				}
				public function check_code(){
					session_start();
					$code = strtoupper($_POST['code']);
					echo $code==$_SESSION['code']?1:0;
					exit;
				}				
				public function upload(){
					$upload = new upload();
					$upload->uploads();
					$return_message =$upload->messge_error();					
                    echo json_encode($return_message);
				}
				public function login(){
					control::login();
				}
				public function register(){	
					control::register();
				}
				public function photo(){
					$photo = new photo();					
				    $photo->change();				    
				}				
			}
?>