<?php


//公有的responseMsg的方法，是我们回复微信的关键。以后的章节修改代码就是修改这个。
    //get post data, May be due to the different environments
/*php版本过高PHP7，此方式$GLOBALS["HTTP_RAW_POST_DATA"]被淘汰*/
//    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//将用户端发送的数据保存到变量postStr中，由于微信端发送的都是xml，使用postStr无法解析，故使用$GLOBALS["HTTP_RAW_POST_DATA"]获取，最早的形式（原生的），获取post数据，$_POST是经过封装的，为数组
    /*var_dump($postStr);无效果，因为不是在浏览器中发送的而是用户在公众号中发送微信服务器转发到我出服务器中,若直接通过浏览器请求该文件则为get请求，更无效果*/
    $postStr=file_get_contents("php://input"); /* PHP封装的协议php:// */
//    file_put_contents('./data.txt', $postStr);
//    die();
    if (!empty($postStr)) {
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);//将postStr变量进行解析并赋予变量postObj。simplexml_load_string（）函数是php中一个解析XML的函数，SimpleXMLElement为新对象的类，LIBXML_NOCDATA表示将CDATA设置为文本节点，CDATA标签中的文本XML不进行解析
        $fromUsername = $postObj->FromUserName;//将微信用户端的用户名赋予变量FromUserName
        $toUsername = $postObj->ToUserName;//将你的微信公众账号ID赋予变量ToUserName
        $keyword = trim($postObj->Content);//将用户微信发来的文本内容去掉空格后赋予变量keyword
        $time = time();//将系统时间赋予变量time
        //构建XML格式的文本赋予变量textTpl，注意XML格式为微信内容固定格式，详见文档;定义模板？？？
        $textTpl = "<xml>  
                            <ToUserName><![CDATA[%s]]></ToUserName>  
                            <FromUserName><![CDATA[%s]]></FromUserName>  
                            <CreateTime>%s</CreateTime>  
                            <MsgType><![CDATA[%s]]></MsgType>  
                            <Content><![CDATA[%s]]></Content>  
                            <FuncFlag>0</FuncFlag>  
                            </xml>";
        //39行，%s表示要转换成字符的数据类型，CDATA表示不转义
        //40行为微信来源方
        //41行为系统时间
        //42行为回复微信的信息类型
        //43行为回复微信的内容
        //44行为是否星标微信
        //XML格式文本结束符号
        if (!empty($keyword))//如果用户端微信发来的文本内容不为空，执行46--51否则52--53
        {
            $msgType = "text";//回复文本信息类型为text型，变量类型为msgType
            $contentStr = "Welcome to qiyaminblog!";//我们进行文本输入的内容，变量名为contentStr，如果你要更改回复信息，就在这儿
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);//将XML格式中的变量分别赋值。注意sprintf函数
            echo $resultStr;//输出回复信息，即发送微信（响应回去，微信公众平台）
        } else {
            echo "Input something...";//不发送到微信端，只是测试使用
        }

    } else {
        echo "";//回复为空，无意义，调试用
        exit;
    }


//签名验证程序    ，checkSignature被18行调用。官方加密、校验流程：将token，timestamp，nonce这三个参数进行字典序排序，然后将这三个参数字符串拼接成一个字符串惊喜shal加密，开发者获得加密后的字符串可以与signature对比，表示该请求来源于微信。


?>