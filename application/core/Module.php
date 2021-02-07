<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HMVC Module 类
 *
 * HMVC 核心对象
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		Hex
 * @category	HMVC
 * @link		http://codeigniter.org.cn/forums/thread-1319-1-2.html
 */
class CI_Module {

    //返回信息code值
    const OK                  = 200;  //Express SUCCESS
    const ERR_PARAM           = 201;  //Express PARAM
    const ERR_EMPTY           = 202;  //Express EMPTY
    const ERR_VERIFICATION    = 203;  //Express TOKEN


	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		// 实例化自己的 Loader 类
		$CI =& get_instance();
		$this->load = clone $CI->load;

		// CI 系统对象采用引用传递的方式，直接赋值给 Module。
		// 当然也可以采用 clone 的方式，可能需要根据不同项目做权衡。
		foreach ($CI->load->get_base_classes() as $var => $class)
		{
			// 排除 Loader 类，因为已经 clone 过了
			if ($var == 'loader')
			{
				continue;
			}
			// 赋值给 Module
			$this->$var =& $CI->$var;
		}
		// 处理自动装载的类库和模型
		$autoload = array_merge($CI->load->_ci_autoload_libraries, $CI->load->_ci_autoload_models);
		foreach ($autoload as $item)
		{
			if (!empty($item) and isset($CI->$item))
			{
				$this->$item =& $CI->$item;
			}
		}

		// 处理数据库对象
		if (isset($CI->db))
		{
			$this->db =& $CI->db;
		}

		// 利用 PHP5.6 的反射机制，动态确定 Module 类名和路径
		$reflector = new ReflectionClass($this);

		$path = substr(dirname($reflector->getFileName()), strlen(realpath(APPPATH.'modules').DIRECTORY_SEPARATOR));
		$class_path = implode('/', array_slice(explode(DIRECTORY_SEPARATOR, $path), 0, -1));
		$class_name = $reflector->getName();

		// 通知 Loader 类，Module 就绪
		$this->load->_ci_module_ready($class_path, $class_name);

		// 把自己放到全局超级对象中
		$CI->$class_name = $this;

		log_message('debug', "$class_name Module Class Initialized");
	}



    /**
     * 输出json数据
     * @param $code
     * @param $message
     * @param null $data
     */
    public  function outputToJson( $code, $message, $data = '', $other = array())
    {

        $arr = array(
            'code'  => $code,
            'msg'   => $message,
            'data'  => $data,
        );
        if(!empty($other) && is_array($other)){
            $arr = array_merge($arr,$other);
        }

        die(json_encode($arr));
    }
}

// END Module Class

/* End of file Module.php */
/* Location: ./application/core/Module.php */
