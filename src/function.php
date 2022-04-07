<?php

/*
|--------------------------------------------------------------------------
| BDB-Frame | sdk/txz | Notice（Version 1.0.1）:
|--------------------------------------------------------------------------
|
| The SDK contains functions, classes, APIs, and demos.
| Copy and paste for use
| Welcome to use. If you have any questions, please contact the community
|
|--------------------------------------------------------------------------
| Version pull：
| composer require txz/sdk
| composer require txz/sdk:1.0.1
| composer require "txz/sdk:x.x.*"
*/




/**
 * 默认生成用户名
 * @author bruce.D
 * @param string $names
 * @return mixed
 * @remarks names存在默认值，反之自己定义
 */
function getUserName($names = ''){
    $nameRand = date('YmdHis') . rand(100, 999);
    $grade = '用户';
    if (empty($names)){
        $name = $grade . $nameRand;
    }else{
        $name = $names . $nameRand;
    }
    return $name;

}



/**
 * 数组中元素 & 对象中属性，拼接成url形式字符串
 * @author bruce.D
 * @param $params
 * @return array
 * @remarks  kSort排序（可删），返回结果，例：a=Dog&c=Horse&d=Cat
 */
function weiXin_buildSign($params){
    if (empty(empty($params))){
        return [];
    }
    ksort($params);

    return http_build_query($params);
}



/**
 * 随机字符串
 * @author bruce.D
 * @param string $str
 * @return string
 * @remarks 返回16位随机字符串，可调整位数
 */
function uniqueString($str = '')
{
    return substr(md5($str ? $str : uniqid(mt_rand())), 8, 16);

}



/**
 * 手机号验证，是否符合规范
 * @author bruce.D
 * @param $mobile
 * @return mixed
 * @remarks 手机号规则验证，返回true & false
 */
function isMobile($mobile)
{
    if (!is_numeric($mobile)) {
        return false;
    }

    return preg_match('#^12[\d]{9}$|^13[\d]{9}$|^14[\d]{9}$|^15[\d]{9}$|^16[\d]{9}$|^17[\d]{9}$|^18[\d]{9}$|^19[\d]{9}$#', $mobile) ? true : false;
}



/**
 * 邮箱验证，是否符合规范
 * @param $mail
 * @return mixed
 * @author bruce.D
 */
function isMail($mail){
    return preg_match('/^\w+([-+.]+\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $mail) ? true : false;
}



/**
 * 变量格式化
 * @param $k 检测值
 * @param $param array
 * @param string $type
 * @return mixed
 * @remarks 例如： param_isset('demo',$param),
 * @author bruce
 */
function param_isset($k, $param, $type = '')
{

    $return = isset($param[$k]) ? trim($param[$k]) : '';

    //时间戳为空传null
    if ($type == 'date') {
        $return = $return ? date("Y-m-d H:i:s", $return) : null;
    }

    return $return;
}


/**
 * 根据时间处格式化时间
 * @param int $timestamp
 * @return false|string
 * @author bruce
 */
function formatDataByTimestamp($timestamp = 0){
    return $timestamp ? date('Y-m-d H:i:s',$timestamp) :'';
}


/**
 * 获取客服端ip
 * @author bruce
 */
function getClientIp()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim(current($ip));
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    } else {
        return '';
    }
}


/**
 * 检测（找出）敏感词
 * @param $arr
 * @param $text
 * @return string
 * @remarks 例如：$arr(提前设置好的一个敏感词字符串 '你好，杀'),$text（要检测的话内容）
 * @author bruce
 */
function check_in($arr, $text){
    $keys = explode(',',$arr);
    $result = '不包含';
    if($keys){
        foreach($keys as $key){
            if(strstr($text,$key)!=''){
                $result = '包含敏感词：'.$key;
                break;
            }
        }
    }
    return $result;
}


/**
 * 获取浏览器方法
 * @param string $url
 * @param $type 1获取域名或主机地址 / 2获取网页地址 /3获取网址参数 / 4获取用户代理/ 5获取包含端口号的完整url
 * @return mixed
 * @remarks 例如：$url =  http://localhost/blog/testurl.php?id=5
 * @author bruce
 */
function getBrowserList($url = '',$type){
    if (empty($url) && empty($type)){
        return false;
    }

    $urlResult = '';
    switch ($type){
        case 1:
            $urlResult = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            break;
        case 2:
            $urlResult = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
            break;
        case 3:
            $urlResult = isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : '';
            break;
        case 4:
            $urlResult = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            break;
        case 5:
            $urlResult = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            break;
    }

    return $urlResult;

}

