<?php
class page{  
  public $page_rows;//每页显示条数
  public $self_page;//总页数
  public $total_page;//总页数
  public $url;//URL地址去除page参数
  function __construct($total,$page_rows=5){
  	$this->page_rows=$page_rows;
  	$this->total_page = ceil($total/$page_rows);
  	$this->url = $this->get_url();
  	$this->self_page = isset($_GET['page'])?min($this->total_page,(int)$_GET["page"]):1;
  }
  private function get_url(){
  	$url = __WEB__.'?';
  	$get = $_GET;
  	if(isset($get['page'])){
  		unset($get["page"]);
  	}    
    if(count($get)){      
  	foreach ($get as $a => $v) {
  		$url.=$a."=".$v.'&';
  	}
      return $url;
  }else{   
    return $url;
  }
  
  }
  public function show(){
  	$page = '';
  	for ($i=1; $i <=$this->total_page ; $i++) { 
  		$url = $this->url."page=".$i;
  		if ($i==$this->self_page) {
  			$page.="<span class='select'>{$i}</span>";continue;
  		}
  		$page.="<a class='select_list' href='{$url}'>{$i}</a>";  		
  	}
  	return $page;
  }
  public function limit(){
  	return ($this->self_page-1)*$this->page_rows.','.$this->page_rows;
  }
}
?>