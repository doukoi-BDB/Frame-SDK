<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Other_Schedule_module
 * @remark 以下方法都是参考 - 项目给予毕设使用（快&简易） - 有其他可自行调整
 * @author bruce
 */
class Other_Schedule_module extends CI_Module {


    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        date_default_timezone_set('Asia/ShangHai');
        $this->load->model(array('Date_user_model','Date_list_model','Date_text_model'));
        $this->load->library(array('Snoopy','user_agent','Aes'));

    }

    public function notice(){
	$uid = $this->input->get('uid');

	$data = $this->Date_list_model->getList('*',['uid'=>$uid,'date_notice'=>2],'');
	if(empty($data)){
	     $this->outputToJson(self::ERR_PARAM,'参数错误',[]);	
	}
	
	$date = date('Y-m-d',time());
	$newArray = [];
	foreach($data as $key => $val){
	   if($val['date'] == $date){
		$newArray[] = $val['date'].'-日程内容：'.$val['title'].'-'.$val['content']; 
	  }	  	
	}

	$this->outputToJson(self::OK,'success',$newArray);
	
    }

    public function getTextList(){
	$uid = $this->input->get('uid');
	
	$data = $this->Date_text_model->getList('*',['uid'=>$uid],'');
	if(empty($data)){
	  $this->outputToJson(self::ERR_PARAM,'暂无记事本',[]);   
	}
	foreach($data as &$val){
	 $val['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
	}
	
	 $this->outputToJson(self::OK,'success',$data);

    }
   
    public function insertText(){
	$uid = $this->input->get('uid');
	$title = $this->input->get('title');
	$content = $this->input->get('content');
	$author = $this->input->get('author');

	$insert = [
	   'uid' => $uid,
	  'title' => $title,
	  'content' => $content,
	  'author' => $author,
	  'create_time' => time()
	];

	$data = $this->Date_text_model->insert($insert);
	$this->outputToJson(self::OK,'success',[]);
    }

    public function delText(){
	$uid = $this->input->get('uid');
	$id = $this->input->get('id');
	 $data = $this->Date_text_model->delData(['id'=>$id,'uid'=>$uid]);
   
	$this->outputToJson(self::OK,'success',[]);
    }

    public function region(){
        $user_name = $this->input->get('user_name');
        $user_sex = $this->input->get('user_sex');
        $user_content = $this->input->get('user_content');
        $user_pwd = $this->input->get('user_pwd');
        $user_num = $this->input->get('user_num');
        if (empty($user_name) || empty($user_sex) || empty($user_content) || empty($user_pwd) || empty($user_num)){
            $this->outputToJson(self::ERR_PARAM,'参数错误',[]);
        }

        $userInfo = $this->Date_user_model->getRow('id',['id'=>$user_num]);
        if (!empty($userInfo)){
            $this->outputToJson(self::ERR_PARAM,'请勿重复注册',[]);
        }

        $insert = [
            'user_name' => $user_name,
            'user_sex' => $user_sex,
            'user_content' => $user_content,
            'user_pwd' => md5($user_pwd),
            'user_num' => $user_num,
            'create_time' => time(),
        ];

        $data = $this->Date_user_model->insert($insert);
        if ($data){
            $this->outputToJson(self::OK,'注册成功',[]);
        }

        $this->outputToJson(self::OK,'注册失败，请重试',[]);

    }

    public function login(){
        $user_num = $this->input->get('user_num');
        $user_pwd = $this->input->get('user_pwd');

        if (empty($user_pwd) || empty($user_num)){
            $this->outputToJson(self::ERR_PARAM,'参数错误',[]);
        }

        $md5pwd = md5($user_pwd);
        $userInfo = $this->Date_user_model->getRow('*',['user_num'=>$user_num]);
        if (empty($userInfo)){
            $this->outputToJson(self::ERR_PARAM,'暂无账户信息',[]);
        }
        $userInfoPwd = $userInfo['user_pwd'] ?? '';
        if ($md5pwd != $userInfoPwd){
            $this->outputToJson(self::ERR_PARAM,'密码错误，请检查',[]);
        }

        $uid = $userInfo['id'] ?? 0;
        if (empty($uid)){
            $this->outputToJson(self::OK,'验证失败',[]);
        }

        $this->outputToJson(self::OK,'登录成功',$uid);

    }

    public function getUser(){
        $id = $this->input->get('id');
        if (empty($id)){
            $this->outputToJson(self::ERR_PARAM,'参数错误',[]);
        }

        $userInfo = $this->Date_user_model->getRow('*',['id'=>$id]);
        if (empty($userInfo)){
            $this->outputToJson(self::ERR_PARAM,'暂无账户信息',[]);
        }

        $this->outputToJson(self::OK,'获取成功',$userInfo);

    }

    public function update(){

        $update['update_time'] = time();
        $id = $this->input->get('id');
        if (empty($id)){
            $this->outputToJson(self::ERR_PARAM,'参数错误',[]);
        }
        $user_name = $this->input->get('user_name');
        if (!empty($user_name)){
            $update['user_name'] = $user_name;
        }
        $user_sex = $this->input->get('user_sex');
        if (!empty($user_sex)){
            $update['user_sex'] = $user_sex;
        }
        $user_content = $this->input->get('user_content');
        if (!empty($user_content)){
            $update['user_content'] = $user_content;
        }
        $user_pwd = $this->input->get('user_pwd');
        if (!empty($user_pwd)){
            $update['user_pwd'] = $user_pwd;
        }
        $user_num = $this->input->get('user_num');
        if (!empty($user_num)){
            $update['user_num'] = $user_num;
        }

        $userInfo = $this->Date_user_model->updateData($update,['id'=>$id]);


        $this->outputToJson(self::OK,'修改成功',[]);
    }

    public function sendContent(){
        $title = $this->input->get('title');
        $content = $this->input->get('content');
        $date = $this->input->get('date');
        $date_detail = $this->input->get('date_detail');
        $class_name = $this->input->get('class_name');
        $date_leverl = $this->input->get('date_leverl');
        $date_notice = $this->input->get('date_notice');
        $img = $this->input->get('img');
        $uid = $this->input->get('uid');
        if (empty($uid) || empty($title) || empty($content) || empty($date) || empty($class_name) || empty($date_leverl) || empty($date_notice) || empty($img)){
            $this->outputToJson(self::ERR_PARAM,'参数错误',[]);
        }

        $insertData = [
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'date_detail' => $date_detail,
            'class_name' => $class_name,
            'date_leverl' => $date_leverl,
            'date_notice' => $date_notice,
            'img' => $img,
            'uid' => $uid,
            'create_time' => time(),
        ];

        $insert = $this->Date_list_model->insert($insertData);

        if ($insert){
            $this->outputToJson(self::OK,'发布成功',[]);
        }

        $this->outputToJson(self::OK,'发布失败，请重试',[]);


    }

    public function delContent(){
        $id = $this->input->get('id');
        $uid = $this->input->get('uid');
        if (empty($id) || empty($uid)){
            $this->outputToJson(self::ERR_PARAM,'参数有误',[]);
        }
        $data = $this->Date_list_model->getRow('id',['uid'=>$uid,'id'=>$id]);
        if (empty($data)){
            $this->outputToJson(self::ERR_PARAM,'数据有误',[]);
        }

        $this->Date_list_model->delData(['uid'=>$uid,'id'=>$id]);

        $this->outputToJson(self::OK,'删除成功',[]);

    }

    public function getContent(){

        $get = $this->input->get();
        $size = isset($get['size']) ? $get['size'] : 20;
        $page = isset($get['page']) ? $get['page'] : 1;
        $start = ($page - 1) * $size;

        $uid = $this->input->get('uid');

        if (empty($uid)){
            $where = [];
        }else{
            $where = ['uid'=>$uid];
        }

        $data = $this->Date_list_model->getList('*',$where,$size,$start);
        if (empty($data)){
            $this->outputToJson(self::OK,'暂无数据',[]);
        }
        $this->outputToJson(self::OK,'获取成功',$data);
    }



}
