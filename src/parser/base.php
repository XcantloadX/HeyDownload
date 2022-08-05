<?php
/**
 * 单个音视频解析器基类
 */
abstract class SingleBase
{
    protected string $id;
    protected ?string $cover = null;
    protected ?string $title = null;
    protected ?string $descr = null;
    protected ?string $author = null;
    protected ?int $timestamp = null;
    protected $urls;

    protected int $code = 0;
    protected string $msg = "success";

	//获取相关信息
	/*
	暂定格式：
	{
		...
		
		"data": {
			"title": "题目",
			"description": "简介/描述".
			"coverUrl": "http://baidu.com",
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

    /**
     * 获取播放地址
     */
    public function getUrl() : ?string
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

    /**获取封面地址 */
    public function getCoverUrl() : ?string
    {
        return $this->cover;
    }

    /**
     * 获取作者
     * @return string 若有多个作者将会以“A / B / C / ...”的形式返回
     * */
    public function getAuthor() : ?string
    {
        return $this->author;
    }

    /**获取标题 */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**获取歌词 */
    public function getLyric() : ?string{
	    return null;
    }

    /**是否有歌词 */
    public function hasLryic() : bool{
        return false;
    }

    /**获取翻译歌词 */
    public function hasTranslatedLyric() : bool{
        return false;
    }

    /**是否有翻译歌词 */
    public function getTranslatedLyric() : ?string{
        return null;
    }

}

/**
 * 视频列表/歌单解析器基类
 */
abstract class ListBase
{
    protected string $id;

}
