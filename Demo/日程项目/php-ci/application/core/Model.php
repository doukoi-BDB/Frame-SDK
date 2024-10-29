<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author      ExpressionEngine Dev Team
 * @copyright   Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license     http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * 重写的 Model 类库
 *
 * 此类库相对于原始 Model 类库，主要是增加了对 HMVC 的支持
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @author      Hex
 * @category    HMVC
 * @link        http://codeigniter.org.cn/forums/thread-1319-1-2.html
 */
class CI_Model {

    /**
     * Constructor
     *
     * @access public
     */
    function __construct()
    {
        $reflector = new ReflectionClass($this);
        $class_name = $reflector->getName();

        $CI =& get_instance();

        if (!empty($CI->load->_ci_module_models[$class_name]))
        {
            $this->_ci_module_class = $CI->load->_ci_module_models[$class_name];
        }

        log_message('debug', "CI_Model Class Initialized");
    }

    /**
     * __get
     *
     * Allows models to access CI's loaded classes using the same
     * syntax as controllers.
     *
     * @param   string
     * @access private
     */
    function __get($key)
    {
        $CI =& get_instance();

        // 如果在模块里找到这个 key 则直接返回这个 key
        // 为了和全局控制器隔离，所以模块中找不到就不再全局中找了
        if (!empty($this->_ci_module_class))
        {
            $module_class_name = $this->_ci_module_class;
            return $CI->$module_class_name->$key;
        }

        return $CI->$key;
    }
}
// END Model Class

/* End of file Model.php */
/* Location: ./application/core/Model.php */