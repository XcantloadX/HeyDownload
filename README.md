# HeyDownload
PHP 音视频解析  
**有待重写**  
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
**注意：下面的 bool 类型参数中非空表示 true，空表示 false**  

string `url`：待解析 URL  
bool `redirect`：是否直接重定向到目标地址（可以直接放在 video/audio 标签的 src 中）（前提是对方没有设置 Referer 限制）。
下面的暂时不能用  
bool `raw`：不统一返回格式，按照原服务器的返回结果返回  
array `action`：动作，包括：`Default`、`GetUrl`、`GetCover`、`GetLyric`、`GetInfo`，可以叠加多个，使用英文逗号分割，不区分大小写  
### 返回
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
        "author": "小魂",
        "cover": "https://www.example.com/image.png"
    }
}
```
`type`：类型，目前只有 `audio`，`video`  
`urls`：所有的解析结果，可能包含不同的画质/音质  
`cover`：封面 URL，若无/不支持则返回空字符串  

## 参考项目/文章
* [QQ音乐API分析之-加密参数分析(sign计算)](https://blog.csdn.net/qq_23594799/article/details/111477320)
* [musicApi](https://github.com/ygCHenDns/musicApi)