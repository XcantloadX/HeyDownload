<?php

//所有解析器的基类
abstract class ParserBase
{	
	//进行解析
	public abstract function parse();
	
	//获取可用视频的列表
	/*
	格式(json)：
	{
		[
		{
			"id": "sd",
			"name": "高清",
			"width": 480,
			"height": 320,
			"url": "http://example.com"
		},
		{
			...
		},...
		]
	}
	*/
	public abstract function getList();
	
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
	public abstract function getInfo();
	
	//获取视频地址
	public abstract function getUrl();
}
?>