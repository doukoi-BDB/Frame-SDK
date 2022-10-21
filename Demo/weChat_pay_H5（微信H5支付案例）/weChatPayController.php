<?php
header('Content-type:text/html; Charset=utf-8');

/*
 * --------------------------
 *  本案例是 微信h5 支付的demo
 * -----------------------------
 *
 * @remark ：代码均可优化，抽出去做服务，所以这里是案例哦！！！抬杠来加群，当粉丝在抬杠
 * @remark : 常见错误如下
 * 1.网络环境未能通过安全验证，请稍后再试（原因：终端IP(spbill_create_ip)与用户实际调起支付时微信侧检测到的终端IP不一致）
 * 2.商家参数格式有误，请联系商家解决（原因：当前调起H5支付的referer为空）
 * 3.商家存在未配置的参数，请联系商家解决（原因：当前调起H5支付的域名与申请H5支付时提交的授权域名不一致）
 * 4.支付请求已失效，请重新发起支付（原因：有效期为5分钟，如超时请重新发起支付）
 * 5.请在微信外打开订单，进行支付（原因：H5支付不能直接在微信客户端内调起）
 *
 *
 * @author bruce.D（清风）
 *
 * 欢迎加入社群【公告下有联系方式】、一起聊聊
 *
 */




/**
 * 更新如下配置信息，配置信息可以抽出去，放代码该放的位置
 * @author bruce
 */
$conf = [
    'mchid'     => '', //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
    'appid'     => '', //微信支付申请对应的公众号的APPID
    'appKey'    => '', //微信支付申请对应的公众号的APP Key
    'apiKey'    => '', //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
    'outTradeNo'=> '', //你自己的商品订单号
    'payAmount' => '', //付款金额，单位:元 例如：0.01
    'orderName' => '', //订单标题 例如：支付测试
    'notifyUrl' => '', //付款成功后的回调地址(不要有问号) 例如：http://www.xxx.com/wx/notify.php
    'returnUrl' => '', //付款成功后，页面跳转的地址 例如：http://www.baidu.com
    'wapUrl'    => '', //WAP网站URL地址
    'wapName'   => '', //WAP 网站名 例如：H5支付

];


$wxPay = new weChatPayService($conf['mchid'],$conf['appid'],$conf['apiKey']);
$wxPay->setTotalFee($conf['payAmount']);
$wxPay->setOutTradeNo($conf['outTradeNo']);
$wxPay->setOrderName($conf['orderName']);
$wxPay->setNotifyUrl($conf['notifyUrl']);
$wxPay->setReturnUrl($conf['returnUrl']);
$wxPay->setWapUrl($conf['wapUrl']);
$wxPay->setWapName($conf['wapName']);

$mwebUrl= $wxPay->createJsBizPackage($conf['payAmount'],$conf['outTradeNo'],$conf['orderName'],$conf['notifyUrl']);
echo "<h1><a href='{$mwebUrl}'>点击跳转至支付页面</a></h1>";
exit();
