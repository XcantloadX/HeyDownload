<?php
require(__ROOT__."/lib/util.php");
require(__ROOT__."/parser/parserBase.php");

//获取对象实例
function newCloudMusic(){
	return new CloudMusic();
}

class CloudMusic extends ParserBase{
	private $songid;
	
	//从视频链接创建
	public function createFromUrl($url){
		$start = strpos($url, "?id=");
		$end = strpos($url, "userid=") > 0 ? strpos($url, "userid=") - strlen("userid=") + 2 : strlen($url) - $start;
		$this->songid = substr($url, $start + strlen("?id="), $end - $start);
	}
	
	//解析
	public function parse(){
		
	}
	
	//返回可用清晰度列表
	//格式参见：ParserBase.php
	public function getList(){
		$list = array(
			"name" => "null", //暂不支持查询名称
			"url" => $this->getUrl(),
			"width" => -1,
			"height" => -1
		);
		
		return $list;
	}
	
	//返回视频链接
	public function getUrl(){
		return "http://music.163.com/song/media/outer/url?id=".$this->songid;
	}
}