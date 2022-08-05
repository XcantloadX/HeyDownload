<?php
require "./lib/simple_html_dom.php";

class CloudMusic extends SingleBase{

    public function __construct($url){
        $url = str_replace("/#/", "/", $url);
		
		$params = getUrlParams($url);
		if(!array_key_exists("id", $params))
			fail("链接无效。", 400);
		
		$this->id = $params["id"];
        $this->parse();
    }

	//解析
	public function parse(){
        $ret = Requests::get("https://music.163.com/song?id=" . $this->id, array("User-Agent"=>UA_WIN10_EDGE))->body;
		$html = new simple_html_dom();
		$html->load($ret);
		$obj = $html->find("script[type=application/ld+json]", 0); //寻找第一个 type=application/ld+json 的 script
		$json = $obj->innertext;
		$json = json_decode($json);
		
        
		$this->cover = $json->images[0];
		$this->title = $json->title;
		$this->descr = $json->description;
		
		$html->clear();
	}
	
	//返回视频链接
	public function getUrls() : array{
		return array("urls" => array(array("quality" => "unknown", "url" => "http://music.163.com/song/media/outer/url?id=".$this->id)));
	}

    public function getType() : string{
        return "audio";
    }
}