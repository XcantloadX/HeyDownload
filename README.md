# HeyDownload
PHP 音视频解析  
**有待重写**  
解析脚本在 `parser ` 下面  

## 已支持站点
* 优酷
* 好看视频
* 网易云音乐
* QQ 音乐

## 已知问题
优酷：`客户端非法，201`，貌似是调用频率太快，触发了防爬，建议十分钟之后再试

## API
`GET /get.php`
### 参数
**注意：下面的 bool 类型参数中非空表示 true，空表示 false**  

string `url`：待解析 URL  
bool `redirect`：是否直接重定向到目标地址（可以直接放在 video/audio 标签的 src 中）（前提是对方没有设置 Referer 限制）。  
array `actions`：动作，包括：`default`、`getUrl`、`getCoverUrl`、`getLyric`等，可以叠加多个，使用英文逗号分割，**区分大小写**。可省略，默认为 `default`。    
### 返回
例如：`/get.php?url=https://y.qq.com/n/ryqq/songDetail/004Jb2Ra312pz3`
```json
{
    "code": 0,
    "msg": "success",
    "data": {
        "urls": [
            {
                "quality": "unknown",
                "url": "http://ws.stream.qqmusic.qq.com/C400003oglTe4Zwe7o.m4a?guid=5932328698&vkey=F0BE6D912306C28F73F53D80C562E8936A1B147F9622626686C3D306F13CDEC6A6973419166C7B6E96467E660644CD1A5807A2BA58000910419166C7B6E96467E660644CD1A5807A2BA58000910&uin=&fromtag=120032"
            },
            {
                "quality": "unknown",
                "url": "http://isure.stream.qqmusic.qq.com/C400003oglTe4Zwe7o.m4a?guid=5932328698&vkey=F0BE6D912306C28F73F53D80C562E8936A1B147F96E660644CD1A5807A2BA58000910&uin=&fromtag=1222626686C3D306F13CDEC6A6973419166C7B6E96467E660644CD1A5807A2BA58000910&uin=&fromtag=120032"
            }
        ],
        "cover": "https://y.qq.com/music/photo_new/T002R300x300M000002ZktSL4BNi8X.jpg?max_age=2592000",
        "title": "夏日未命名",
        "author": "小魂",
        "type": "audio"
    }
}
```
`type`：类型，目前只有 `audio`，`video`  
`urls`：所有的解析结果，可能包含不同的画质/音质  
`cover`：封面 URL，若无/不支持则返回空字符串  

## 命令行调用
**Windows 下需要先运行 `chcp 65001` 切换到 UTF-8 编码以避免输出乱码**  
调用方法：  
```
php ./get.php arg1 arg2 ...
```  
如  
```
php ./get.php url=https://y.qq.com/n/ryqq/songDetail/004Jb2Ra312pz3
```

## 参考项目/文章
* [QQ音乐API分析之-加密参数分析(sign计算)](https://blog.csdn.net/qq_23594799/article/details/111477320)
* [musicApi](https://github.com/ygCHenDns/musicApi)