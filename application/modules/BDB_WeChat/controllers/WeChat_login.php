<?php defined('BASEPATH') OR exit('No direct script access allowed');


/*
 * 本控制器 主要案例微信授权登录
 * @param  no-1、小程序手机号授权登录
 * @param  no-2、公众号授权登录
 *
 * 公众号：八点半技术站
 * github ：https://github.com/doukoi-BDB  （俩个仓库，可以来个 star 呗）
 *
 * 有问题加入BDB-Frame 社区联系我。
 */

class BDB_WeChat_WeChat_login_module extends CI_Module {



    /*
     * 手机号授权登录
     *
     * 备注：大部分逻辑操作，不同语言参照即可，本代码为php
     */

    private $AppID = 'xxxxx';
    private $AppSecret = 'xxxxxxx';


    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        date_default_timezone_set('Asia/ShangHai');

        //WXBizDataCrypt 解密类
        $this->load->library(array('Snoopy','User_agent','WXBizDataCrypt'));

        $this->load->model(array('User_model','User_wechat_model'));

        //获取传输过来的json数据
        $this->data = file_get_contents('php://input');
        $this->post = json_decode($this->data,true);
    }


    /*
     * 小程序授权登录
     */
    public function WechatLogin(){

        $post = $this->input->post();

        $js_code = isset($post['jscode']) ? $post['jscode'] : '';
        if (empty($js_code)){
            $this->outputToJson(self::ERR_PARAM,'JsCode Cannot be empty',[]);
        }

        //用appid 、serect 、code 调用 WeChat api
        $wx_url = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid={$this->AppID}&secret={$this->AppSecret}&js_code={$js_code}&grant_type=authorization_code");
        $wx_data = json_decode($wx_url,true);

        $wx_data['errcode'] = isset($wx_data['errcode']) ? $wx_data['errcode'] : '';

        $openid = isset($wx_data['openid']) ? $wx_data['openid'] : '';

        if ($wx_data['errcode'] == 40163){
            $this->outputToJson(self::ERR_PARAM,'code已使用');
        }else{
            $insert = array(
                'session_key' => isset($wx_data['session_key']) ? $wx_data['session_key'] : '',
                'openid' => $openid,
                'time' => date('Y-m-d H:i:s'),
                'app_id' => $this->AppID
            );

            //临时表 - 下面解密需要用到
            $this->User_wechat_model->insert($insert);

        }


        //-----解密手机号

        $encryptedData = isset($post['encryptedData']) ? $post['encryptedData'] : '';
        $iv = isset($post['iv']) ? ($post['iv']) : '';

        if(!$encryptedData || !$iv){
            $this->outputToJson(self::ERR_PARAM,' encryptedData or iv 参数有误');
        }

        $arr = $this->User_wechat_model->getRow('*',array('openid'=>$openid));
        if (empty($arr)){
            $this->outputToJson(self::ERR_PARAM,'解密失败，openid faild error',[]);
        }

        $where = array('openid'=>$openid);
        //取临时表值
        $data_arr_list = $this->User_wechat_model->getList('*',$where,'');

        if (empty($data_arr_list)){
            echo json_encode(['code'=>404,'errcode' => -404]);exit;
        }else{
            $data_arr = max($data_arr_list);
        }

        $sessionKey = isset($data_arr['session_key']) ? $data_arr['session_key'] : '';
        $openid = isset($data_arr['openid']) ? $data_arr['openid'] : '';
        $data_arr_app_id = isset($data_arr['app_id']) ? $data_arr['app_id'] :  '';

        //实列化 解密类
        $pc = new WXBizDataCrypt();
        $errCode = $pc->decryptData($data_arr_app_id,$sessionKey, $encryptedData, $iv, $data_arr );

        //解密成功，获取得到手机号
        if ($errCode == 0) {

            $res = json_decode($data_arr,true);
            $result = $this->User_model->getRow('*',array('user_phone'=>$res['phoneNumber']));

            if ($result){
                // 这里判断用户是否存在，可以写一些更新信息逻辑
                $token = '小程序生成了token 规则，根据业务来看哈，每个业务不同';

                if (empty($result['user_name'])){
                    $name = '举的列子哈';
                    $this->User_model->updateData(array('user_name'=>$name),array('user_phone'=>$result['user_phone']));

                }
                $this->outputToJson(self::OK,'success',array('token'=>$token,'openId'=>$openid));

            }else {

                $insert = array(
                    'user_uid' => 11111,
                    'user_name' => '用户'.rand(111,999),
                    'user_openid'    => $openid,
                    'user_phone'    => $result['user_phone'],
                    'user_img'=>'https://xxxx.JPG',
                    'create_time' => date('Y-m-d')
                );

                //为小程序创建用户
                $insert_data = $this->User_model->insert($insert);

                if ($insert_data) {
                    $this->outputToJson(self::OK, 'success', array('token'=>$token,'openId'=>$openid));
                }
            }

        }else{
            $this->outputToJson(self::ERR_PARAM,'error ~~~',[]);
        }


    }



}


/*
 * 小程序- 手机号授权登录就到这里也就结束了，是不是看起来非常容易，对于公众号来说更是，都不需要解密类。
 *
 * 所谓的unioid 是在开放平台绑定，才会有的哦，有其他疑问，技术社群欢迎交流～～～
 */




