<?php defined('BASEPATH') OR exit('No direct script access allowed');
//phpinfo();die;


class BDB_Basics_Frame_redis_content_module extends CI_Module {


    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        date_default_timezone_set('Asia/ShangHai');

        $this->load->model(array('User_model'));

        $this->load->library('RedisTool');
        $this->redistool->selectDb(1);

    }



    /*
     * 注册
     * @param phone \code \ pwd \pwd_second
     * 备注：本api ，主要基础逻辑操作，具体根据业务情况而定，方式多种多样
     */
    public function registe_data(){

        $phone = $this->input->post('phone');
        if (empty($phone)){
            $this->outputToJson(self::ERR_PARAM,'手机号不可为空',[]);
        }else{
            $user_isset = $this->User_model->getRow('id',array('phone'=>$phone));
            if ($user_isset){
                $this->outputToJson(self::ERR_PARAM,'您已经注册，请勿重新注册',[]);
            }
        }

        $code = $this->input->post('code');
        if (empty($code)){
            $this->outputToJson(self::ERR_PARAM,'验证码不可以为空',[]);
        }

        $data = $this->Code_model->getList('id,create_time,code',array('phone'=>$phone,'status'=>1),'');
        if (empty($data)){
            $this->outputToJson(self::ERR_PARAM,'data is empty',[]);
        }
        $data_code = max($data);
        if ($code != $data_code['code']){
            $this->outputToJson(self::ERR_PARAM,'验证码有误',[]);
        }

        $pwd = $this->input->post('pwd');
        if (empty($pwd)){
            $this->outputToJson(self::ERR_PARAM,'密码不可以为空',[]);
        }

        $pwd_second = $this->input->post('pwd_second');
        if (empty($pwd_second)){
            $this->outputToJson(self::ERR_PARAM,'二次密码不可以为空',[]);
        }

        if (md5($pwd) != md5($pwd_second)){
            $this->outputToJson(self::ERR_PARAM,'俩次密码不相同，重新输入',[]);
        }

        //token 方式多种多样 - jwt - 自封装token类 - controller内部处理 ---
        $arr['phone'] = $phone;
        $arr['time'] = date('Ymd');
        $result = json_encode($arr);

        // ... 这里你的token 加密规则一系列处理
        $token = '*******加密中*******';


        //redis - string 存储 token 标识，为期 7200s
        $ex_time = 7200;
        $token_key = 'token_'.$token;
        $this->redistool->setExpire($ex_time);
        $this->redistool->setKey($token_key,$token);


        $insert = [
            'username' => '用户'.rand(1111,9999),
            'content' => '这个人很懒，什么也没有留下',
            'sex' => 1,
            'pwd' => md5($pwd),
            'photo' => 'https://xxx.jpg',
            'phone' => $phone,
            'user_uid' => '*****用户标识*****'
        ];

        $param = $this->User_model->insert($insert);
        if ($param){
            //验证码 处理 - 因为验证码的使用
            //根据业务而定，存储redis & mysql - 使用规则
            $this->outputToJson(self::OK,'注册成功',$token);
        }else{
            $this->outputToJson(self::ERR_PARAM,'注册失败',[]);
        }

        $this->outputToJson(self::ERR_PARAM,'error',[]);

    }



}

?>

