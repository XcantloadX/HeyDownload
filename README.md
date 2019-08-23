# HeyDownload
目前只是优酷视频解析脚本  

## 在线 Demo
[点击这里查看在线 Demo~](http://qwq233.tk/demos/ykvid/)  

## 使用
访问 `video.php?vid=视频ID`，返回视频链接  

## 说明
下面是**即将实现的**API说明  

API 地址：`video.php`
方法：`GET`

### 通用参数

|参数|说明|已实现|
|--|--|--|
|`vid`|视频 ID|是|
|`url`|视频 url|否|
|`type`|视频网站|否|
|`quality`|清晰度|否|
|`raw`|返回内容不经过处理|否|


### 返回结果

示例：   
```json
{
	"code": 状态码,
	"msg": "提示信息",
	"video":{
		"url": "地址",
		"quality": "视频清晰度"
	}
}
```