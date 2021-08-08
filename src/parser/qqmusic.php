<?php
//TODO 把 curl 改为 Request 库
//TODO 支持 QQ 音乐另一种 URL https://y.qq.com/n/yqq/song/004Of2MN0iIjD2.html
class QQMusic extends ParserBase {
    public function __construct(string $url){
        $this->id = substr(_get("url"), -14);
        $this->run();
    }

    function run(){
        //post 请求参数
        $json = <<<DATA
{
    "comm": {
        "cv": 4747474,
        "ct": 24,
        "format": "json",
        "inCharset": "utf-8",
        "outCharset": "utf-8",
        "notice": 0,
        "platform": "yqq.json",
        "needNewCode": 1,
        "g_tk_new_20200303": 53883209,
        "g_tk": 53883209
    },
    "req_1": {
        "module": "vkey.GetVkeyServer",
        "method": "CgiGetVkey",
        "param": {
            "guid": "5932328698",
            "songmid": [
                "$this->id"
            ],
            "songtype": [
                0
            ],
            "loginflag": 0,
            "platform": "20"
        }
    },
    "req_2": {
        "method": "get_song_detail_yqq",
        "module": "music.pf_song_detail_svr",
        "param": {
            "song_mid": "$this->id"
        }
    }
}
DATA;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://u.y.qq.com/cgi-bin/musics.fcg?sign=".makeSign($json), //使用 http，https 有几率造成证书错误之类的
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"',
                'sec-ch-ua-mobile: ?0',
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.101 Safari/537.36',
                'Content-Type: application/x-www-form-urlencoded',
                'Referer: https://y.qq.com/'
            ),
        ));

        $response = curl_exec($curl);

        //输出 raw
        if(_has("raw"))
            exit($response);

        curl_close($curl);
        $ret = json_decode($response);

        //错误检查
        if($ret->code != 0 || $ret->req_1->code != 0 || (is_string($response) && $response == "Not Found")){
            fail("Request qqmusic server failed. code=".$ret->code, 500);
        }

        //获取所有歌手
        $singers = $ret->req_2->data->track_info->singer;
        $singerStr = "";
        foreach ($singers as $v) {
            if($singerStr != ""){
                $singerStr = $singerStr." / ";
            }
            $singerStr = $singerStr.$v->name;
        }
        $this->author = $singerStr;

        //获取封面
        //QQ 音乐中单曲封面 = 专辑封面！
        $albumMid = $ret->req_2->data->track_info->album->mid;
        if($albumMid == ""){
            $this->cover = "";
        }else{
            $this->cover = "https://y.qq.com/music/photo_new/T002R300x300M000$albumMid.jpg?max_age=2592000";
        }

        $this->title = $ret->req_2->data->track_info->title;
        $this->urls = array(
            0 => array("quality" => "unknown", "url" => $ret->req_1->data->sip[0] . $ret->req_1->data->midurlinfo[0]->purl),
            1 => array("quality" => "unknown", "url" => $ret->req_1->data->sip[1] . $ret->req_1->data->midurlinfo[0]->purl),
        );
    }

    public function getInfo() : array
    {
        return array(
            "type" => "audio",
            "urls" => $this->urls,
            "title" => $this->title,
            "author" => $this->author,
            "cover" => $this->cover
        );
    }

    public function getType() : string
    {
        return "audio";
    }

    /** 获取歌词
     */
    public function getLyric() : array{
        $ret = json_decode(Requests::get("https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric_new.fcg?_=1626762812506&cv=4747474&ct=24&format=json&inCharset=utf-8&outCharset=utf-8&notice=0&platform=yqq.json&needNewCode=1&songmid=$this->id",
            array("Referer" => "https://y.qq.com/"))->body);
        return array("lyric" => base64_decode($ret->lyric));
    }

    public function getLyricUrl() : array{
        return array("lyricUrl" => null);
    }
}

/** 生成 sign
 * @param string $param 要提交的参数
 * @return string 生成的 sign
 * @throws Exception 由 bin2hex() 抛出
 */
function makeSign(string $param) : string{
    //https://blog.csdn.net/qq_23594799/article/details/111477320
    //sign = "zza" + 随机十位字符 + md5($param);
    return "zza".bin2hex(random_bytes(5)).md5("CJBPACrRuNy7".$param);
}
