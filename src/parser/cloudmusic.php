<?php
require "./lib/simple_html_dom.php";

function init(){
    $cm = new CloudMusic(_get("url"));
    if(_has("redirect"))
        redirect($cm->getUrl());
    $response = makeResponse(0, 
                        "成功",
                        "audio", 
                        array(array("quality" => "default", "url" => $cm->getUrl())),
                        $cm->getInfo()
                       );
    
    succeed($response);
}

class CloudMusic{
	private $songid;
	
	private $cover;
	private $title;
	private $descr;
	private $author;
	private $timestamp;
	
    public function __construct($url){
        $url = str_replace("/#/", "/", $url);
		
		$params = getUrlParams($url);
		if(!array_key_exists("id", $params))
			fail("链接无效。", 400);
		
		$this->songid = $params["id"];
        $this->parse();
    }
    
	
	//解析
	public function parse(){
		$html = new simple_html_dom();
		$html->load(file_get_contents("https://music.163.com/song?id=" . $this->songid));
		$obj = $html->find("script[type=application/ld+json]", 0); //寻找第一个 type=application/ld+json 的 script
		
		$json = $obj->innertext;
		$json = json_decode($json);
		
        
		$this->cover = $json->images[0];
		$this->title = $json->title;
		$this->descr = $json->description;
		
		$html->clear();
	}
	
	//返回可用清晰度列表
	//格式参见：ParserBase.php
	public function getList(){
		$list = array(
			"name" => null, //暂不支持查询名称
			"url" => $this->getUrl(),
			"width" => -1,
			"height" => -1
		);
		
		return $list;
	}
	
	//获取相关信息
	public function getInfo(){
		return array(
			"title" => $this->title,
			"cover" => $this->cover,
			"description" => $this->descr,
			"author" => $this->author,
			"timestamp" => $this->timestamp
		);
	}
	
	//返回视频链接
	public function getUrl(){
		return "http://music.163.com/song/media/outer/url?id=".$this->songid;
	}
}