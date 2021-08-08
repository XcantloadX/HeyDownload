<?php
class HaoKan extends ParserBase {
	private $json;
	private $watch;
	private $like;

	public function __construct(string $url)
    {
        $start = strpos($url, "vid=");
        $this->id = substr($url, $start + strlen("vid="), strlen($url) - $start);
        $this->parse();
    }
	
	//解析
	public function parse(){
		
		$html = file_get_contents("https://haokan.baidu.com/v?vid=".$this->id);
		
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

	public function getUrls() : array{
        $newList = array();
        $oldList = $this->json->curVideoMeta->clarityUrl;

        //遍历清晰度
        for($i = 0; $i < count($oldList); $i++){
            //$hw = explode("$$", $obj->vodVideoHW); //高宽
            $url = array(
                "quality" => $oldList[$i]->title,
                "url" => $oldList[$i]->url
            );
            $newList[$i] = $url;
        }

        return array("urls" => $newList);
	}

	public function getUrl() : string{
        return $this->json->curVideoMeta->playurl;
    }

	public function getLikeCount() : array{
	    return array("likeCount" => $this->like);
    }

    public function getWatchCount() : array{
        return array("watchCount" => $this->watch);
    }

    public function getType(): string
    {
        return "video";
    }
}