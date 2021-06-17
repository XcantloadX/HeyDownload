# HeyDownload
PHP 音视频解析  
解析脚本在 `parser ` 下面  
[Demo](http://api.qwq123.top/demo/heydownload/index.html)（PS：Demo 部署在国外服务器上，QQ 音乐无法使用）

## 已支持站点
* 优酷
* 好看视频
* 网易云音乐
* QQ 音乐

## 已知问题
“客户端非法，201”：貌似是调用频率太快，触发了防爬，建议十分钟之后再试

## API
`GET /get.php`
### 参数
string `url`：待解析 URL  
bool `redirect`：是否直接重定向到目标地址（可以直接放在 video/audio 的 src 中）  
bool `origin`：是否返回原服务器返回内容  
不同网站可能会有其他不同的参数  
### 返回格式
例如：`/get.php?url=https://y.qq.com/n/ryqq/songDetail/004Jb2Ra312pz3`
```json
{
    "code": 0,
    "data": {
        "type": "audio",
        "urls": [
            {
                "quality": "unknown",
                "url": "..."
            },
            {
                "quality": "unknown",
                "url": "..."
            }
        ],
        "title": "夏日未命名",
        "author": "小魂"
    }
}
```
`type`：类型，目前只有 `audio`，`video`  
`urls`：所有的解析结果，可能包含不同的画质/音质  

## 参考项目/文章
* [QQ音乐API分析之-加密参数分析(sign计算)](https://blog.csdn.net/qq_23594799/article/details/111477320)
