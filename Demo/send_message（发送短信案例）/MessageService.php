<?php

/**
 * 短信服务类
 * Class MessageService
 * @remark 此类替换参数，可直接使用
 * @author bruce
 */
class MessageService{


    /**
     * 调发送验证码服务
     * @param $phone
     * @param $code
     * @param $key
     * @param $secret
     * @author bruce
     */
    public static function  sendSms($phone = '',$code = '',$key = '',$secret = ''){


        $params = [];

        //阿里云的AccessKey
        $accessKeyId = $key;

        //阿里云的Access Key Secret
        $accessKeySecret = $secret;

        //要发送的手机号
        $params["PhoneNumbers"] = $phone;

        //签名，第三步申请得到
        $params["SignName"] = 'xxxx';

        //模板code，第三步申请得到
        $params["TemplateCode"] = 'xxxxx';

        //模板的参数，注意code这个键需要和模板的占位符一致
        $params['TemplateParam'] = Array (
            "code" => $code
        );

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();
        try{
            // 此处可能会抛出异常，注意catch
            $content = $helper->request(
                $accessKeyId,
                $accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                ))
            // fixme 选填: 启用https
            // ,true
            );
            $res=array('errCode'=>0,'msg'=>'ok');
            if($content->Message != 'OK'){
                $res['errCode'] = 1;
                $res['msg']=$content->Message;
            }
            echo json_encode(array('code'=>200,'msg'=>'发送成功','data'=>[]));exit;
        }catch(\Exception $e){
            echo '短信接口请求错误';exit;
        }

    }



}