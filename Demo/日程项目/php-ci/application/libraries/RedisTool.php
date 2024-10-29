<?php
/**
  * [redis 服务 操作]
  * @author [bruce]
  */

class RedisTool
{
	private $CI;

    private $_connection;
	
    private $debug = FALSE;

    private $expire = 86400;

    private $is_connect = false;

    private $time_out = 1;

    const CRLF = "\r\n";

	public function __construct()
	{

        $this->CI = get_instance();

        $this->CI->load->config('redis');

        $redis_server = $this->CI->config->item('redis_ip');

        $redis_port = $this->CI->config->item('redis_port');

        $redis_passwd = $this->CI->config->item('redis_passwd');

        if ( !$redis_server  || !$redis_port) {
            echo "没有设置redis.php 文件";exit;
        }


        $this->_connection = new Redis();

        $this->is_connect = @$this->_connection->connect($redis_server,$redis_port,$this->time_out);

        if( $redis_passwd != '' && $this->is_connect) {
           $this->_connection->auth($redis_passwd); 
        }


	}

    public function test() {
        if ( !$this->is_connect ) {
            return $this->is_connect;
        }
        return $this->_connection->ping();
    }

    public function setExpire($time) {
        $this->expire = $time;
    }

    public function setKey($key,$value) {
       return $this->_connection->set($key,$value,$this->expire);
    }

    public function sKey($key,$value) {
        return $this->_connection->set($key,$value);
    }

    public function getKey($key) {
        return $this->_connection->get($key);
    }

    public function getKeyttl($key) {
        return $this->_connection->ttl($key);
    }

    public function delKey($key){
        return $this->_connection->del($key);
    }

    /*获取给定模式的key列表*/
    public function searchKey($key){
        return $this->_connection->keys($key);
    }

    /*merge*/
    public function mergeKey($new_key,$key_arr){
        return $this->_connection->pfmerge($new_key,$key_arr);
    }

    /*PFCOUNT*/
    public function pfcountKey($key){
        return $this->_connection->pfcount($key);
    }

    /*rPush*/
    public function setr_Push($key, $value1){
        return $this->_connection->rPush($key, $value1);
    }

    /*rPush*/
    public function setl_Push($key, $value1){
        return $this->_connection->lPush($key, $value1);
    }

    /*lRange*/
    public function list_lRange($key, $start, $end){
        return $this->_connection->lRange($key, $start, $end);
    }

    /*lLen*/
    public function list_Len($key){
        return $this->_connection->lLen($key);
    }
    /*KEYS*/
    public function keysKey($key){
        return $this->_connection->keys($key);
    }

    /*getHKey*/
    public function getHKey($key,$field){
        return $this->_connection->hget($key,$field);
    }

    /*setHKey*/
    public function setHKey($key,$field,$value){
        return $this->_connection->hset($key,$field,$value);
    }

    /*isexists*/
    public function isexists($key){
        return $this->_connection->exists($key);
    }
    
    /*isHexists*/
    public function isHexists($key, $hashKey){
        return $this->_connection->hexists($key, $hashKey);
    }

    /*SELECT_DB*/
    public function selectDb($num){
        return $this->_connection->select($num);
    }

    /*pfAdd*/
    public function pfAdd($key,$array){
        return $this->_connection->pfAdd($key,$array);
    }

    /*pfCount*/
    public function pfCount($key){
        return $this->_connection->pfCount($key);
    }

    /*ZRANGEBYSCORE*/
    public function zRangeByScore( $key, $start, $end){
        return $this->_connection->zRangeByScore( $key, $start, $end);
    }

    /*SMEMBERS SMEMBERS */
    public function sGet($key){
        return $this->_connection->sMembers($key);
    }


    /**EXPIRE**/
    public function Expire( $key, $ttl ){
        return $this->_connection->expire( $key, $ttl );
    }

    public function setAdd($key, $value1){
        return $this->_connection->sAdd( $key, $value1, $value2 = null, $valueN = null );
    }

    // 移除集合中的一个或多个成员元
    public function sersRem($key, $member1){
        return $this->_connection->sRem( $key, $member1, $member2 = null, $memberN = null );
    }

    public function exPireAt($key, $timestamp){
        return $this->_connection->expireAt($key, $timestamp);
    }

    public function print_code() {
        echo '$this->load->library(\'Redistool\');<br/>';
        echo '$this->redistool->setExpire(10);<br/>';
        echo '$this->redistool->setKey(\'ddd\',\'bb\');<br/>';
        echo '$this->redistool->getKey(\'ddd\');<br/>';
        echo '$this->redistool->getKeyttl(\'ddd\');<br/>';
    }

}
