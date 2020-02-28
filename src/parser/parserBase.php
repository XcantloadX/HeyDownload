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
	
	//获取视频地址
	public abstract function getUrl();
}
?>