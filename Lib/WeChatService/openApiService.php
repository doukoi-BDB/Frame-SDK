<?php

/**
 * Class openApiService
 * @remark  openApi 管理 ，详细请看浏览公众号开发文档
 * @remark  微信开发文档：https://developers.weixin.qq.com/doc/offiaccount/openApi/clear_quota.html
 * @remark  代码自行优化
 * @author bruce
 */
class openApiService extends BaseService{


    protected $config;

    const QUOTA_FIRST = 1;
    const QUOTA_SECOND = 2;
    const QUOTA_THIRD = 3;


   public function __construct(){

       $this->config = [
           '1' => 'https://api.weixin.qq.com/cgi-bin/clear_quota?access_token=',
           '2' => 'https://api.weixin.qq.com/cgi-bin/openapi/quota/get?access_token=',
           '3' => 'https://api.weixin.qq.com/cgi-bin/openapi/rid/get?access_token=',
       ];

   }



    /**
     * 统一管理api的quota
     *
     * @param type：1 清空公众号/小程序/第三方平台等接口的每日调用接口次数。
     * @param type：2 本接口用于查询公众号/小程序/第三方平台等接口的每日调用接口的额度以及调用次数。
     * @param type：3 本接口用于查询调用公众号/小程序/第三方平台等接口报错返回的 rid 详情信息，辅助开发者高效定位问题。
     *
     * @param array data 接口请求需要用到的access_token (数组传值，随时可扩展)
     * 示例：$data = array('access_token'=>xxx,'type对应的参数'=>xxx);
     * 1) type=1 时，传参数access_token ； 传参数appid （要被清空的账号的appid） 。
     * 1) type=2 时，传参数access_token ； 传参数cgi_path（api的请求地址，例如"/cgi-bin/message/custom/send";不要前缀，也不要漏了"/",否则都会76003的报错） 。
     * 1) type=3 时，传参数access_token ； 传参数rid （调用接口报错返回的rid） 。
     *
     * @return mixed
     * @author bruce
     * @remark  注意事项:查哪一方的数据（公众号/小程序/第三方平台）就取哪一方对应的access_token
     */
    public function openApiQuotaResult($type = '',$data = []){

        if (empty($type) && empty($data)){
            $this->outputToJson(202,'param is empty',[]);
        }

        if (!in_array($type,[self::QUOTA_FIRST,self::QUOTA_SECOND,self::QUOTA_THIRD])){
            $this->outputToJson(202,'param is error',[]);
        }

        //获取对应url
        $url = $this->openApiQuotaUrl($type);
        if (empty($url)){
            $this->outputToJson(202,'url empty ！！！',[]);
        }

        //请求获取结果
        return $this->openApiQuotaData($url,$data);



    }


    /**
     * 检测对应值返回的url
     * @param string $type
     * @return mixed
     * @author bruce
     */
    private function openApiQuotaUrl($type = ''){
        if (empty($type)){
            return false;
        }

        $result = array_search($type,$this->config);
        if (empty(!$result)){
            return $result;
        }else{
            return false;
        }

    }


    /**
     * 获取对应url 结果
     * @param string $url
     * @param array $data
     * @return mixed
     * @author bruce
     */
    private function openApiQuotaData($url = '',$data = []){

        //完整链接
        $urlResult = $url.$data['access_token'];
        unset($data['access_token']);

        return $this->curl_post($urlResult,$data);

    }


}