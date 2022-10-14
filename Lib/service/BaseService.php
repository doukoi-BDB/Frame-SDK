<?php

/**
 * 基类
 * Class BaseService
 * @author bruce
 */
class BaseService{

    use SingletonTrait;


    /**
     * @param $url[接口的url]
     * @param $data [传递的参数]
     * @return mixed
     * @author bruce
     */
    public function curl_post($url, $data) {

        //初始化
        $ch = curl_init();

        $connectTimeOut = 20;

        //设置url
        curl_setopt($ch, CURLOPT_URL, $url);

        //在 FTP 请求执行完成后，在服务器上执行的一组array格式的 FTP 命令
        curl_setopt($ch, CURLOPT_POST, 1);

        //设置头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //不验证 SSL 证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //不验证 SSL 证书域名
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //在尝试连接时等待的秒数。设置为0，则无限等待。
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeOut);

        //全部数据使用HTTP协议中的 "POST" 操作来发送。
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $return = curl_exec($ch);

        //检查是否有错误发生

        if (curl_errno($ch)){
            $msg = 'initialization error ！！！';

            //curl 错误日志
            $json = array(
                'code' => 1001,
                'msg'  => '请求的api发生错误',
                'error'=> ''
            );
            //记录http_code
            $info = curl_getinfo($ch);
            if(intval($info['http_code']) != 200){
                $msg = 'http_code:'.$info['http_code'];
            }
            $msg .= curl_error($ch);
            $json['error'] = $msg;

        }
        curl_close($ch);

        return array(
            'data'     => isset($return) ? $return : '',
            'infoMsg'  => isset($json) ? $json : '',
        );

    }




    /**
     * 输出json数据
     * @param $code
     * @param $message
     * @param null $data
     * @param array $other
     * @author bruce
     */
    public  function outputToJson( $code, $message, $data = '', $other = array())
    {
         header("Content-type: application/json; charset=utf-8");
        $arr = array(
            'code'  => $code,
            'msg'   => $message,
            'data'  => $data,
        );
        if(!empty($other) && is_array($other)){
            $arr = array_merge($arr,$other);
        }

        die(json_encode($arr));
    }

}