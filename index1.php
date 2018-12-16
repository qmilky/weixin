<?php
//    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//将用户端放松的数据保存到变量postStr中，由于微信端发送的都是xml，使用postStr无法解析，故使用$GLOBALS["HTTP_RAW_POST_DATA"]获取
        $postStr = file_get_contents("php://input");
//    file_put_contents('./data.txt', $postStr);
//    die();
    if (!empty($postStr)){
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);//将postStr变量进行解析并赋予变量postObj。simplexml_load_string（）函数是php中一个解析XML的函数，SimpleXMLElement为新对象的类，LIBXML_NOCDATA表示将CDATA设置为文本节点，CDATA标签中的文本XML不进行解析
        $fromUsername = $postObj->FromUserName;//将微信用户端的用户名赋予变量FromUserName
        $toUsername = $postObj->ToUserName;//将你的微信公众账号ID赋予变量ToUserName
        $keyword = trim($postObj->Content);//将用户微信发来的文本内容去掉空格后赋予变量keyword
        $time = time();//将系统时间赋予变量time

        switch($keyword){
            case '美女':
                $textTpl="<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>";
              $msgType = 'image';
              $pdo = new PDO('mysql:host=47.94.149.55;dbname=weixin;charset=utf8;port=3306', 'root', 'bian0714');
              $stmt = $pdo->query('SELECT * FROM sucai WHERE type="image"');
              $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $key = array_rand($arr);  /*array_rand() 函数返回数组中的随机键名，或者如果您规定函数返回不只一个键名，则返回包含随机键名的数组。*/
              $MediaId = $arr[$key]['media_id'];
              $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$MediaId);
//              file_put_contents('./data.txt', $resultStr);
              echo $resultStr;  /*此步必须有*/
              break;
            case '你好':
                $textTpl = "<xml>  
                            <ToUserName><![CDATA[%s]]></ToUserName>  
                            <FromUserName><![CDATA[%s]]></FromUserName>  
                            <CreateTime>%s</CreateTime>  
                            <MsgType><![CDATA[%s]]></MsgType>  
                            <Content><![CDATA[%s]]></Content>  
                            <FuncFlag>0</FuncFlag>  
                            </xml>";
                $msgType = "text";
                $contentStr="你好";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);//将XML格式中的变量分别赋值。注意sprintf函数
                echo $resultStr;//输出回复信息，即发送微信（响应回去，微信公众平台）
                file_put_contents('./data1.txt', $resultStr);
                break;
            default:
              break;
        };


    }else {
        echo "";//回复为空，无意义，调试用
        exit;
    }
