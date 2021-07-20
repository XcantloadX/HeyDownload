<?php
//入口函数
function init($url, $redirect){
	$hk = new HaoKan();
	$hk->createFromUrl(_get("url"));
	$hk->parse();
	if(_has("redirect")){
		redirect($hk->getUrl());
	}
	else{
		$info = $hk->getInfo();
		$response = array(
			"code" => 0,
			"data" => array(
			  "type" => "video",
			  "urls" => $hk->getList(),
			  "title" => $info["title"],
			  "author" => $info["author"]
			)
		  );
		  succeed($response);
	}
}

class HaoKan{
	private $vid;
	private $json;
	
	private $cover;
	private $title;
	private $descr;
	private $author;
	private $timestamp;
	
	private $watch;
	private $like;
	

	//从视频链接创建
	public function createFromUrl($url){
		$start = strpos($url, "vid=");
		$this->vid = substr($url, $start + strlen("vid="), strlen($url) - $start);
	}
	
	//解析
	public function parse(){
		
		$html = file_get_contents("https://haokan.baidu.com/v?vid=".$this->vid);
		
		$start = strpos($html, "window.__PRELOADED_STATE__ = "); //json 开始
		$end = strpos($html, ";", $start); //json 结束
		$json = substr($html, $start + strlen("window.__PRELOADED_STATE__ = "), $end - $start - + strlen("window.__PRELOADED_STATE__ = "));
		
		$this->json = json_decode($json);
		
		//获取视频信息
		$this->title = $this->json->curVideoMeta->title;
		$this->cover = $this->json->curVideoMeta->poster;
		$this->cover = substr($this->cover, 0, strpos($this->cover, "@"));
		$this->timestamp = $this->json->curVideoMeta->publish_time;
		
		$this->watch = $this->json->curVideoMeta->playcnt;
		$this->like = $this->json->curVideoMeta->like;
	}
	
	//返回可用清晰度列表
	public function getList(){
		$newList = array();
		$oldList = $this->json->curVideoMeta->clarityUrl;
		
		//遍历清晰度
		foreach($oldList as $obj){
			$hw = explode("$$", $obj->vodVideoHW); //高宽
			$singleVideo = array(
				"quality" => $obj->title,
				"url" => $obj->url
			);
			array_push($newList, $singleVideo); //加入总数组
		}
		
		return $newList;
	}
	
	//获取相关信息
	public function getInfo(){
		return array(
			"title" => $this->title,
			"cover" => $this->cover,
			"description" => $this->descr,
			"author" => $this->author,
			"timestamp" => $this->timestamp,
			"special" => array(
				"watch" => $this->watch,
				"like" => $this->like)
		);
	}
	
	//返回视频链接
	public function getUrl(){
		return $this->json->curVideoMeta->playurl;
	}
}