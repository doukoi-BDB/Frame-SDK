<?php

/**
 *  ------------------------------------------------------------
 *  下面分享的是一套发送邮件案列
 *
 *  如下代码只缺少类的引用，以163邮箱为基础，其他粘贴复制即可使用。
 *  有任何疑问，欢迎社区讨论（社群加入方式，底部通知）
 *
 *  ------------------------------------------------------------
 */

class sendEmailController{


    /**
     * 发送邮件
     * @param email 可配置
     * @remark 因提供demo案列，你们拿去后可以优化，比如：输出结果、邮箱从配置文件读取 等
     * @author bruce.D
     */
    public function sdk_email()
    {

        $post = $this->input->post();
        $email = isset($post['email']) ? $post['email'] : 'xxxxx@163.com';

        //验证邮箱
        $pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        if (!preg_match($pattern, $email)) {
            $returnData['code'] = 202;
            $returnData['message'] = '邮箱格式不正确';
            echo json_encode($returnData);
            exit;
        }

        $pageList = $this->path_list($email);
        $flag = $this->sendMail($email, 'xx合作申请-官网来源：', $pageList);
        if ($flag) {
            $returnData['code'] = 200;
            $returnData['message'] = '发送成功';
            echo json_encode($returnData);
            exit;
        } else {
            $returnData['code'] = 202;
            $returnData['message'] = '发送失败';
            echo json_encode($returnData);
            exit;
        }
        die;
    }


    /**
     * 加载发送邮件
     * @param $to
     * @param $title
     * @param $content
     * @return bool
     * @throws Exception
     * @remark 这里面需要注意的是 PHPMailer 、 SMTP ，这俩需要引入，我的写法是ci ，你们需要调整一下这里。
     * 以及里面 是xxx 的需要替换掉，都是以163 为基础，非常简单。
     * @author bruce
     */
    function sendMail($to,$title,$content){
        //引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
        $this->load->library('PHPMailer');
        $this->load->library('SMTP');
        //实例化PHPMailer核心类
        $mail = new PHPMailer(true);

        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        //$mail->SMTPDebug = 1;

        //使用smtp鉴权方式发送邮件
        $mail->isSMTP();

        //smtp需要鉴权 这个必须是true
        $mail->SMTPAuth=true;

        //链接qq域名邮箱的服务器地址
        $mail->Host = 'smtp.ym.163.com';

        //设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';

        //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
        $mail->Port = 465;

        //设置smtp的helo消息头 这个可有可无 内容任意
        // $mail->Helo = 'Hello smtp.qq.com Server';

        //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
        $mail->Hostname = 'http://www.lsgogroup.com';

        //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
        $mail->CharSet = 'UTF-8';

        //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = 'xxxx';

        //smtp登录的账号 这里填入字符串格式的qq号即可
        $mail->Username ='pm@163.com';


        //smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
        $mail->Password = 'xxxxx';

        //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
        $mail->From = 'pm@163.com';

        //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
        $mail->isHTML(true);

        //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
        $toArr = explode(',',$to);
        foreach($toArr as $key=>$val){
            $mail->addAddress($val,'');
        }
        //添加多个收件人 则多次调用方法即可
        // $mail->addAddress('xxx@163.com','lsgo在线通知');

        //添加该邮件的主题
        $mail->Subject = $title;

        //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
        $mail->Body = $content;

        //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
        //$mail->addAttachment('a.jpg','a.jpg');
        //同样该方法可以多次调用 上传多个附件
        // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');

        $status = $mail->send();

        //简单的判断与提示信息
        if($status) {
            return true;
        }else{
            return false;
        }
    }





    /**
     * 页面数据
     * @param $email
     * @return mixed
     * @remark 这里就是页面发送后，邮箱内容中看的样子，自由发挥哈
     * @author bruce
     */
    public function path_list($email){
        $time = date('y-m-d h:i:s',time());
        $pageList = '
      <!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <title></title>
        <style media="screen">
        body{ width:100%; height: 100%; background: #f5f6f8; margin:0 ;font-size:12px;}
        body a{ color:#0097ef; text-decoration: none;}
        p{ padding:0;margin:0;}
        ul li {padding:0;margin: 0; list-style: none;}

        .container-fluid{ width:95%; margin:30px auto;}
       body .spe { color:#696a6c; text-decoration: underline;}
        </style>
      </head>
      <body>

      <div id="page-wrapper" class="">
        <div class="container-fluid">
          <!-- title -->
          <div class="sdk_title">
            <h5>xx</h5>
          </div>
          <!-- 欢迎part -->
            <div class="sdk_routine">
              <p>合作申请</p>
              <p>时间：'.$time.'</p>
              <p>收件人：官网系统'.$email.'</p>
              <p>内容：
                 <p>联系人姓名：'.xx.'</p>
                 <p>公司名称：'.xx.'</p>
                 <p>联系人电话：'.xx.'</p>
                 <p>微信号：'.xx.'</p>
              </p>
            </div>
          </div>
          <!-- footer -->
        </div>
      </div>
      </body>

    </html>
    ';
        return $pageList;
    }



}



