<?php
class Aes {
    public function __construct(){
        $CI =& get_instance();
        $this->aesKey = $CI->config->item('aesKey');
        $this->aesIv  = $CI->config->item('aesIv');
    }

    //加密
    public function encrypt($str){
        return base64_encode(openssl_encrypt($str, 'aes-128-cbc', $this->aesKey, OPENSSL_RAW_DATA, $this->aesIv));
    }

    //解密
    public function decrypt($str){
        $encryptedText = base64_decode($str);
        return openssl_decrypt($encryptedText, 'aes-128-cbc', $this->aesKey, OPENSSL_RAW_DATA, $this->aesIv);
    }

}