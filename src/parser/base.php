<?php

//所有解析器的基类
abstract class ParserBase
{
    protected $id;
    protected $cover;
    protected $title;
    protected $descr;
    protected $author;
    protected $timestamp;
    protected $urls;

    protected $code = 0;
    protected $msg = "success";

	//获取相关信息
	/*
	暂定格式：
	{
		...
		
		"data": {
			"title": "题目",
			"description": "简介/描述".
			"cover": "http://baidu.com",
			"author": "作者",
			"timestamp": 发布/上传 时间戳,
			"special":{
				//这里是该网站独有数据，如哔哩哔哩的投币
				"like": 点赞数,
				"watch": 播放量
			}
		}

	}
	*/

    public function getUrl() : string
    {
        return $this->urls[0];
    }

	//获取视频地址
	public function getUrls() : array
    {
        return array("urls" => $this->urls);
    }

    public function getCode() : int{
        return $this->code;
    }

    public function getMsg() : string{
        return $this->msg;
    }

    public abstract function getType();

    public function getCoverUrl() : array
    {
        return array("cover" => $this->cover);
    }

    public function getAuthor() : array
    {
        return array("author" => $this->author);
    }

    public function getTitle() : array
    {
        return array("title" => $this->title);
    }

    public function getLyric() : array{
	    return array("lyric" => null);
    }

    public function getLyricUrl() : array{
        return array("lyricUrl" => null);
    }


}
