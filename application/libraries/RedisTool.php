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

	//测试redis 连接
    public function test_ping() {
        if ( !$this->is_connect ) {
            return $this->is_connect;
        }
        return $this->_connection->ping();
    }


    public function setExpire($time) {
        $this->expire = $time;
    }

    //SET 命令用于设置给定 key 的值。如果 key 已经存储其他值， SET 就覆写旧值，且无视类型。
    public function setKey($key,$value) {
       return $this->_connection->set($key,$value,$this->expire);
    }

    //SET 命令用于设置给定 key 的值。如果 key 已经存储其他值， SET 就覆写旧值，且无视类型。
    public function sKey($key,$value) {
        return $this->_connection->set($key,$value);
    }

    // Get 命令用于获取指定 key 的值。如果 key 不存在，返回 nil 。如果key 储存的值不是字符串类型，返回一个错误。
    public function getKey($key) {
        return $this->_connection->get($key);
    }

    //DEL 命令用于删除已存在的键。不存在的 key 会被忽略。
    public function delKey($key){
        return $this->_connection->del($key);
    }

    //TTL 命令以秒为单位返回 key 的剩余过期时间。
    public function getKeyttl($key) {
        return $this->_connection->ttl($key);
    }

    //获取给定模式的key列表
    public function searchKey($key){
        return $this->_connection->keys($key);
    }

    //list - Rpush 命令用于将一个或多个值插入到列表的尾部(最右边)。
    public function setl_Push($key, $value1){
        return $this->_connection->lPush($key, $value1);
    }

    //list -  Lrange 返回列表中指定区间内的元素，区间以偏移量 START 和 END 指定
    public function list_lRange($key, $start, $end){
        return $this->_connection->lRange($key, $start, $end);
    }

    //list - Llen 命令用于返回列表的长度。 如果列表 key 不存在，则 key 被解释为一个空列表，返回 0 。 如果 key 不是列表类型，返回一个错误。
    public function list_Len($key){
        return $this->_connection->lLen($key);
    }


    //Hget 命令用于返回哈希表中指定字段的值- HGET KEY_NAME FIELD_NAME
    public function getHKey($key,$field){
        return $this->_connection->hget($key,$field);
    }

    // Hset 命令用于为哈希表中的字段赋值 。
    //如果哈希表不存在，一个新的哈希表被创建并进行 HSET 操作。
    //如果字段已经存在于哈希表中，旧值将被覆盖。 - HSET KEY_NAME FIELD VALUE
    public function setHKey($key,$field,$value){
        return $this->_connection->hset($key,$field,$value);
    }

    //Hexists 命令用于查看哈希表的指定字段是否存在。 -  HEXISTS KEY_NAME FIELD_NAME
    public function isHexists($key, $hashKey){
        return $this->_connection->hexists($key, $hashKey);
    }


    //EXISTS 命令用于检查给定 key 是否存在。
    public function isexists($key){
        return $this->_connection->exists($key);
    }
    

    //Select 命令用于切换到指定的数据库，数据库索引号 index 用数字值指定，以 0 作为起始索引值。
    public function selectDb($num){
        return $this->_connection->select($num);
    }

    // set - Smembers 命令返回集合中的所有的成员。 不存在的集合 key 被视为空集合。
    public function sGet($key){
        return $this->_connection->sMembers($key);
    }

    //Expire 命令用于设置 key 的过期时间，key 过期后将不再可用。单位以秒计。
    public function Expire( $key, $ttl ){
        return $this->_connection->expire( $key, $ttl );
    }

    // set - Sadd 命令将一个或多个成员元素加入到集合中，已经存在于集合的成员元素将被忽略。
    //假如集合 key 不存在，则创建一个只包含添加的元素作成员的集合。
    //当集合 key 不是集合类型时，返回一个错误。
    public function setAdd($key, $value1){
        return $this->_connection->sAdd( $key, $value1, $value2 = null, $valueN = null );
    }

    //set -  Srem 命令用于移除集合中的一个或多个成员元素，不存在的成员元素会被忽略。
    public function sersRem($key, $member1){
        return $this->_connection->sRem( $key, $member1, $member2 = null, $memberN = null );
    }

    //Expireat 命令用于以 UNIX 时间戳(unix timestamp)格式设置 key 的过期时间。key 过期后将不再可用。
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
