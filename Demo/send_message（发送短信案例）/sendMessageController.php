<?php

/**
 * 短信发送控制器
 * Class sendMessageController
 * @remark 所有控制器代码均可优化，因我提供的是一个案列代码，有任何疑问社群沟通
 * @author bruce
 */
class sendMessageController{


    //这里可以抽到配置文件读取，短信的配置信息，不知道：百度&谷歌
    const ACCESS_KEY = '';  //短信的accessKey
    const ACCESS_SECRET = ''; //短信的accessSecret

    /**
     * 发送验证码
     * @remark 代码优化，例如：1）统一返回结果；2）配置常量等
     * @author bruce
     */
    public function sendCode(){

        $randCode = rand(111111,999999);

        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        if (empty($phone)){
            $returnData['code'] = 202;
            $returnData['message'] = '手机号不可为空';
            echo json_encode($returnData);exit;
        }

        //这里可以加其他逻辑，是否黑名单，手机号是否符合规则

        //这里定义就完全可以抽出去&时间戳处理（这么写也可以，为了提醒规则）
        $start_time = date('Y-m-d H:i:s',mktime(0,0,0));
        $end_time = date('Y-m-d H:i:s',mktime(23,23,59));

        $where['create_time >='] =$start_time;
        $where['create_time <='] =$end_time;
        $where['phone'] = $phone;

        //model 替换
        $data = $this->Code_model->getCount($where);
        //验证验证码次数
        if ($data > 4 ){
            $returnData['code'] = 202;
            $returnData['message'] = '今日验证次数过多，明日来试';
            echo json_encode($returnData);exit;
        }

        //数据插入
        $insert_data = [
            'code' => $randCode,
            'phone' => $phone,
            'create_time' => date('Y-m-d H:i:s'),
        ];

        //model 替换
        $this->Code_model->insert($insert_data);

        //发送验证码
        $sms = new MessageService();
        $flag = $sms->sendSms($phone,$randCode,self::ACCESS_KEY,self::ACCESS_SECRET);
        $flag_result = json_decode($flag,true);

        if ($flag_result['msg'] == 'ok'){
            return $flag_result;
        }else{
            $returnData['code'] = 202;
            $returnData['message'] = '发送失败';
            echo json_encode($returnData);exit;
        }


    }




}