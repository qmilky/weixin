<?php
include './Http.class.php';
$type = $_FILES['img']['type'];
if($type=='image/jpeg'){$upload_type='image';}
else if ($type == 'audio/mp3') {
    $upload_type = 'voice';
} elseif ($type == 'video/mp4') {
    $upload_type = 'video';
}
$data = Http::get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx19720d1a2767778b&secret=7da9699a327f35be2e7cfceb24a7f559');
//json格式的字符串转换为数组，
$arr = json_decode($data,true);
$access_token=$arr['access_token'];
//var_dump($access_token);die;
    /*素材管理*/
//  var_dump($_FILES);die;
//获取上传文件的路径
$ext = pathinfo($_FILES['img']['name'])['extension'];
$newfilename = date('Y-m-d',time()).mt_rand(100000,999999).'.'.$ext;
//移动文件
//var_dump($_FILES['img']);
define('ROOT',dirname(__FILE__).'/uploads/');/*存储图片的文件必须是777，否则move_uploaded_file（）函数是无法执行成功的*/

if(is_uploaded_file($_FILES['img']['tmp_name'])){
//    echo ROOT.$newfilename.'<br>';
//    echo $_FILES['img']['tmp_name'];
    $res1 = move_uploaded_file($_FILES['img']['tmp_name'],ROOT.$newfilename);
//    var_dump($res1);
    //准备路径
    if(class_exists('\CURLFile')){  //此处单引号必须加  否则无法返回mediaid
        $body = array('fieldname'=>new \CURLFile(ROOT.$newfilename,$type));
    } else{
        $body = array('fieldname'=>'@'.ROOT.$newfilename);
    }
   $res =  Http::post('https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$upload_type,$body);//http请求方式：POST/FORM，使用https，上传到微信服务器的接口；
//    var_dump($res);
   /*将微信服务器返回的数据$res存入我处服务器数据库,因为MediaId是动态的，每次刷新都会变动，因此存起来，让其变为静态。*/
   $data1 = json_decode($res,true);
//   var_dump($data1);  /*array(3) { ["type"]=> string(5) "image" ["media_id"]=> string(64) "3liT_X3HpfiRsWcrBqoCWbt9vY08jpmvIGpFhzTserAbdMjV3NtvdiY5l_3Ll8o0" ["created_at"]=> int(1520820855) }*/
    $pdo = new PDO('mysql:host=47.94.149.55;dbname=weixin;charset=utf8;port=3306', 'root', 'bian0714');
    $stmt = $pdo->prepare('INSERT INTO sucai(type,media_id,created_at) Values(:type,:media_id,:created_at)');
    $stmt->execute($data1);
    /*var_dump($pdo)此处打印$pdo时为空对象？？？*/
    $id = $pdo->lastInsertId();
//    var_dump($pdo->lastInsertId());
    if($id){
        echo "上传成功";
    }else{
        echo '上传失败';
    }
}else{
    echo '未上传';
};
//调用示例（使用curl命令，用FORM表单方式上传一个多媒体文件）：
//curl -F media=@test.jpg "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=TYPE"