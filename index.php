<?php

//get post data, May be due to the different environments
// $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
$postStr = file_get_contents("php://input");

//extract post data
if (!empty($postStr)) {

    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $keyword = trim($postObj->Content);
    $time = time();

    switch ($keyword) {
        case '美女':
            $textTpl = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>';
            $msgType = 'image';
            $pdo = new PDO('mysql:host=47.94.149.55;dbname=weixin;charset=utf8;port=3306', 'root', 'bian0714');
            $stmt = $pdo->query('SELECT * FROM sucai WHERE type="image"');
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $key = array_rand($arr);
            $mediaId = $arr[$key]['media_id'];
            $str = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $mediaId);
//            file_put_contents('./data.txt',$str);
            echo $str;
            break;
        case '11':
            $textTpl = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Video><MediaId><![CDATA[%s]]></MediaId><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description></Video></xml>';
            $msgType = 'video';
            $title = '西游记';
            $description = '大型古装神话爱情动作悬疑大片';

            $pdo = new PDO('mysql:host=localhost;dbname=wx;charset=utf8;port=3306', 'root', '123456');
            $stmt = $pdo->query('SELECT * FROM sucai WHERE type="video"');
            $arr = $stmt->fetch(PDO::FETCH_ASSOC);
            $mediaId = $arr['media_id'];
            $str = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $mediaId, $title, $description);
            echo $str;
            break;

        case '语音':
            $textTpl = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Voice><MediaId><![CDATA[%s]]></MediaId></Voice></xml>';
            $msgType = 'voice';

            $pdo = new PDO('mysql:host=47.94.149.55;dbname=weixin;charset=utf8;port=3306', 'root', 'bian0714');
            $stmt = $pdo->query('SELECT * FROM sucai WHERE type="voice"');
            $arr = $stmt->fetch(PDO::FETCH_ASSOC);
            $mediaId = $arr['media_id'];
            $str = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $mediaId);
            echo $str;
            break;

        case '来一首歌曲':
            $textTpl = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl></Music></xml>';
            $msgType = 'music';
            $title = '秋裤';
            $description = '秋裤秋裤秋裤秋裤秋裤秋裤';
            $url = 'http://sc1.111ttt.cn/2017/1/11/11/304112003368.mp3';
            $hqurl = 'http://sc1.111ttt.cn/2017/1/11/11/304112003368.mp3';

            $str = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $title, $description, $url, $hqurl);
            echo $str;
            break;

        case '新闻':

            $textTpl = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><ArticleCount>2</ArticleCount><Articles><item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item><item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item></Articles></xml>';
            $msgType = 'news';

            $title1 = '大熊猫尽享冬日暖阳';
            $description1 = '12月20日，26岁的大熊猫“英英”在中国大熊猫保护研究中心都江堰基。';
            $url1 = 'http://picture.youth.cn/qtdb/201712/W020171221266378751876.jpg';
            $hqurl1 = 'http://picture.youth.cn/qtdb/201712/t20171221_11180370.htm';
            $title2 = '威胁成真！美国为何急着同意中国大飞机取得适航证？原因被曝光了';
            $description2 = '据国外媒体报道，加拿大将从澳大利亚采购二手的F-18战机。';
            $url2 = 'https://p2.ssl.cdn.btime.com/t019e38350aabcbc0fe.jpg';
            $hqurl2 = 'https://item.btime.com/m_2s1cmkx9k13';

            $str = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $title1, $description1, $url1, $hqurl1, $title2, $description2, $url2, $hqurl2);
            echo $str;
            break;

        default:
            # code...
            break;
    }

} else {
    echo "";
    exit;
}
