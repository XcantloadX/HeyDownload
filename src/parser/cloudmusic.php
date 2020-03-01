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
		$url = str_replace("/#/", "/", $url);
		
		$params = getUrlParams($url);
		if(!array_key_exists("id", $params))
			die("Invalid Netease Music url.");
		
		$this->songid = $params["id"];
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