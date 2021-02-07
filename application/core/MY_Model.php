<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 公共Model 
 * @auther  Bruce.D
 * @version 2018-07-03
 */
class MY_Model extends CI_Model
{
	protected $master ;
	protected $slave ;
	public $table ;
    private static $dbconnection;


	/**
	 * 加载读写数据库
	 * @auther doutingqiang
	 * @version 2018-07-03
	 */
	function __construct()
	{
		parent::__construct() ;

//		$this->db = $this->slave = $this->master = $this->load->database('default' , TRUE);
        $this->db = $this->slave = $this->master =self::getInstance();
	}

    /***
     * @return 获取数据库实例
     */
    static function getInstance(){
        $CI =& get_instance();
        if(self::$dbconnection){
            return self::$dbconnection;
        }else{
            self::$dbconnection = $CI->load->database('default' , TRUE);
            return self::$dbconnection;
        }
    }


	
	/**
	 * ----------------------------------------------------
	 * 获取数据列表 
	 * ----------------------------------------------------
	 * @param $field(string)(array) 查询字段
	 * @param $where (array) 查询数组
	 * @param $limit(int) 查询条数
	 * @param $offset(int) 开始位置
	 * @param $order(string) 排序
	 * @auther Bruce.D
	 */
	public function getList( $field = '*' , $where = array() , $limit = 1 , $offset = Null , $order = '' , $group = '')
	{
		$this->slave->from( $this->table );
		$this->slave->select( $field );
		$this->like( $where );
		$this->slave->where( $where );
		if(intval( $limit ) > 0){
			$this->slave->limit( $limit , $offset);
		}
	
		if( !empty( $order))
		{
			$this->slave->order_by( $order);
		}
		if( !empty( $group ))
		{
			$this->slave->group_by( $group );
		}
		$rec = $this->slave->get();

		return $rec->result_array();
	}


	/**
	 * ----------------------------------------------------------------
	 * 多表关联查多条数据
	 * ----------------------------------------------------------------
	 * @param $tables (array) array('goods' => 'goods' ,'库名'	=> '别名');
	 * @param $join (array) array('goods.goods_id = detail.goods_id');
	 * @param $field (array) 查询字符串 goods.id
	 * @param $where (array) 查询条件  array('goods.goods_id' => 1)
	 * @param $limit (int) 查询条数
	 * @param $offset (int) 偏移量
	 * @param $order (string)排序
	 * @return (array) 二维数组
	 * @author		Bruce.D
	 */
	public function getJoinList( $tables , $join , $field = "*" , $where , $limit = 1 , $offset = 0 , $order = NULL ,  $group = NULL,$having= array())
	{
		$this->slave->select($field);
		$i = 0;
		foreach( $tables as $k => $v){
			if( $i == 0){
				$this->slave->from( $k." As ".$v);
			}else{
				$this->slave->join( $k." As ".$v , $join[$i-1] , 'left');
			}
			$i++;
		}
		$this->slave->where($where );
		$this->slave->limit($limit , $offset);
		if(isset($order)){
			$this->slave->order_by($order);
		}
		if( !empty( $group ))
		{
			$this->slave->group_by( $group );
		}
        if( !empty( $having )){
            $this->db->having($having);
        }
		$query = $this->slave->get();

		return $query->result_array();
	}



	
	/**
	 * --------------------------------------------------
	 * 获取一条数据 - 1
	 * ---------------------------------------------------
	 * @param $field(string)(array) 查询字段
	 * @param $where (array) 查询数组
	 * @param $order(string) 排序描述			
	 * @author		Bruce.D
	 */
	public function getRow(  $field = '*' , $where = array() , $order = '' )
	{
		$this->slave->from( $this->table );
		$this->slave->select( $field );
		$this->like( $where );
		$this->slave->where( $where );
		$this->slave->limit( 1 );
		if( !empty( $order))
		{
			$this->slave->order_by( $order);
		}
		$rec = $this->slave->get();

		return $rec->row_array();
	}



	/**
	 * --------------------------------------------------
	 * 获取一条数据 - 2
	 * ---------------------------------------------------
	 * @param $field(string)(array) 查询字段
	 * @param $where (array) 查询数组
	 * @param $order(string) 排序描述
	 * @author		Bruce.D
	 */
	public function getOne(  $field = '*' , $where = array() , $order = '' )
	{
	 $result = $this->getRow( $field , $where , $order);
		if( !empty($result))
		{
			foreach($result as $k => $v  )
			{
				return $v;
			}
		}
		
		return '';
	}



	/**
	 * --------------------------------------------------
	 * 获取一条数据
	 * ---------------------------------------------------
	 * @param $field(string)(array) 查询字段
	 * @param $where (array) 查询数组
	 * @param $order(string) 排序描述
	 * @author		Bruce.D
	 */
	public function getJoinOne( $tables , $join , $field = "*" , $where  , $order = NULL )
	{
		$result = $this->getJoinRow( $tables , $join , $field , $where , $order);
	
		foreach($result as $k => $v  )
		{
			return $v;
		}
		return '';
	}



	/**
	 *----------------------------------------------------------------
	 * 多表关联查询一条数据
	 * ---------------------------------------------------------------
	 * @param $tables (array) array('goods' => 'goods' ,'库名'	=> '别名');
	 * @param $join (array) array('goods.goods_id = detail.goods_id');
	 * @param $field (array) 查询字符串 goods.id
	 * @param $where (array) 查询条件  array('goods.goods_id' => 1)
	 * @param $order (string)排序
	 * @return (array) 一维数组
	 * @author		Bruce.D
	 */
	public function getJoinRow( $tables , $join , $field = "*" , $where  , $order = NULL )
	{
		$this->slave->select($field);
		$i = 0;
		foreach( $tables as $k => $v){
			if( $i == 0){
				$this->slave->from( $k." As ".$v);
			}else{
				$this->slave->join( $k." As ".$v , $join[$i-1] , 'left');
			}
			$i++;
		}
		$this->slave->where($where );
		$this->slave->limit( 1 );
		if(isset($order)){
			$this->slave->order_by($order);
		}
		$query = $this->slave->get();
		//echo $this->slave->last_query();
		return $query->row_array();
	}




	/**
	 *
	 * 插入数据
	 * @param $data (array) 添加的数据
	 * @param $new_id (Bool) 是否返回ID
	 * @return (bool)(int) 返回 Bool 或 插入 自增ID
	 * @author		Bruce.D
	 */
	public function insertData( $data , $new_id = FALSE )
	{
		$rec = $this->master->insert( $this->table , $data);

		if( $rec && $new_id){
			return $this->master->insert_id();
		}
		return $rec;
	}




	
	/**
	 *---------------------------------------------
	 * 物理删除数据			
	 * -------------------------------------------
	 * @param $where 删除where条件
	 * @return bool
	 * @author		doutingqiang
	 * @date		2018-07-03
	 */
	public function delData( $where )
	{
		return $this->master->delete( $this->table , $where );
	}

        
        
    /**
    * 简单的查询
    * @param string $filed 查询的字段
    * @param mixed $where 查询条件
    * @return 结果集
    * @author  doutingqiang
    * @date		2018-07-03
    */
    public function select($filed = '*', $where = array()){
       //去除空格
       $arr = explode(",", str_replace(" ", "", $filed));
       $str = "";
       //添加mysql通配符
       foreach($arr as $v){
            $str .="`".$v."`,";
        }
        $fileds = rtrim($str, ",");
        //判断查询条件是数组还是字符串
        if(is_array($where)){
            $w = "";
            //拼装查询条件
            foreach($where as $k => $v){
                $w .= "".$k."='".$v."' and ";
            }
            $w = rtrim($w, " and ");
            $sql = "select ".$fileds." from `t_".$this->table."` where ".$w;               
           }else{
            $sql = "select ".$fileds." from `t_".$this->table."` where ".$where;
           }

          $query = $this->db->query($sql);
          return $query->result_array();
        }



    public function last_query( $table = 'slave')
    {
        $table = ( $table == 'slave')?'slave':'master';
        return $this->$table->last_query();
    }




}
/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
