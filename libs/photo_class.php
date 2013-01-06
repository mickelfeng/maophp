<?php
  class photo{
    protected $img_water;//水印图片的地址
    protected $opcity;//水印的透明度
    protected $position;//水印的坐标
    protected $small_w;//缩放的宽高
    protected $small_h;
    protected $small_type;//0->无剪切缩放  1->剪切缩放,剪切图片,改变图片大小 3->剪切缩放,改变框大小
    protected $mes;//前端传来的配置信息
    protected $img_url;//目标图片的地址
    protected $select;//1->添加水印,0->图片缩放
    protected $put_dir;//处理后的图片存放的文件夹,默认覆盖之前的文件
   public function __construct(){
   	   $this->mes=$_POST;
   	   C(include WWW_CONFIG.'/photo_config.php');       
   	   $this->img_water=$this->mes["img_water"];	   
   	   $this->opcity=$this->mes["opcity"]?$this->mes["opcity"]:C("photo","opcity");
   	   $this->position=$this->mes["position"]?$this->mes["position"]:C("photo","position");
   	   $this->small_w=$this->mes["small_w"]?$this->mes["small_w"]:C("photo","small_w");
   	   $this->small_h=$this->mes["small_h"]?$this->mes["small_h"]:C("photo","small_h");
   	   $this->small_type=$this->mes["small_type"]?$this->mes["small_type"]:C("photo","small_type");
   	   if(is_null($this->mes["img_url"])||is_null($this->mes["select"])){
   	   	echo "缺少必要的参数，（图片地址与选择类型必须要有！）";
   	   	exit;
   	   }
   	   $this->img_url=$this->mes["img_url"];
   	   $this->select=$this->mes["select"];
   	   $this->put_dir=$this->mes["put_dir"]?$this->mes["put_dir"]:dirname($this->mes["img_url"]);
    }
   public function change(){
     if($this->select){
     	if(is_null($this->mes["img_water"])){
     		echo "请指明水印图片的地址";
     		exit;
     	}
        $this->water();
     }else{
        $this->action();        
     }
   
   }
   //图片的缩放
   private function action(){
   	  $file = $this->img_url;
      $this->check($file);
      $canvas = imagecreatetruecolor($this->small_w, $this->small_h);
      $img_info=$this->getImgInfo($file);
      $img_size = $this->getSize($canvas,$img_info);#获得绽放尺寸参数  
      imagecopyresized($canvas, $img_info['res'], 0, 0, 0, 0, $this->small_w, $this->small_h,
      $img_size[0],$img_size[1]);
      $func = "image".$img_info['type'];
      $func($canvas,$this->put_dir."/".basename($this->img_url));
    }

    private function getSize($canvas,$img_info){
        $d_w = $this->small_w;#缩放宽度
		$d_h = $this->small_h;#绽放高度
		$img_w =$img_info[0];#源图宽度
		$img_h=$img_info[1];#源图高度
		switch($this->small_type){
				case 0:
				break;
				case 3:
				if($img_w/$d_w>$img_h/$d_h){
                     $d_h = $img_h*($d_w/$img_w);
                     $this->samll_h=$d_h;
				}else{
				  $d_w = $img_w*($d_h/$img_h);
				  $this->samll_w=$d_w;
				}
				
				break;
				default:
				#取哪个比例大（缩放比例大的是应该裁切的）
				#不裁切的边的缩放比例（即图片/缩略图尺寸）* 裁切方向的缩略尺寸
				if($img_w/$d_w>$img_h/$d_h){
                  $img_w = $img_h/$d_h*$d_w;
				}else{
				  $img_h = $img_w/$d_w*$d_h;
				}
			}
           return array($img_w,$img_h);
    }
    //加水印
    private function water(){
            $file = $this->img_url;
			//验证环境
			$this->check($file);
			//获得图片资源
			$des_info = $this->getImgInfo($file);
			//logo所有信息
			$water_info = $this->getImgInfo($this->img_water);
			$pos = $this->getWaterPos($des_info,$water_info);
			imagecopymerge($des_info['res'], $water_info['res'],$pos[0], $pos[1], 0, 0, $water_info[0], $water_info[1],$this->opcity);			
			$func = "image".$des_info['type'];#jpeg
			$func($des_info['res'],$file);
		}
		#获得水印位置
	private function getWaterPos($des_info,$water_info){
			$pos = $this->position;
			$x =$pos[0];
			$y =$pos[1];
		  if($water_info[0]+$x>=$des_info[0]){
		  	$x = $des_info[0]-$water_info[0];
		  }
		  if($water_info[1]+$y>=$des_info[1]){
		  	$y = $des_info[1]-$water_info[1];
		  }
			return array($x,$y);
		}
 

  
   private function check($file){       
       if(extension_loaded("GD")&& is_file($file) && getimagesize($file)){
         
       }else{
       	 exit("准备条件不符");
       }

   }
   //getImgInfo() 获取图像详细信息，包括图像类想，资源，返回数组
   private function getImgInfo($file){
        $info = getimagesize($file);#获得图片所有信息
		$type = substr(strstr($info['mime'],'/'),1);#获得类型如jpeg
		$func = "imagecreatefrom".$type;#变量函数
		$info['res'] = $func($file);//压入图片资源 
		$info['type']=$type;
		return $info;

   }
}
?>