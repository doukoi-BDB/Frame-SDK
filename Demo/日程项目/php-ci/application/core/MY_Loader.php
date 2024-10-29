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
 * 已扩展的 Loader 类库
 *
 * 此类库相对于原始 Loader 类库，主要是增加了对 HMVC 的支持
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @author      Hex
 * @category    HMVC
 * @link        http://codeigniter.org.cn/forums/thread-1319-1-2.html
 */
class MY_Loader extends CI_Loader {

    var $_ci_is_inside_module = false;  // 当前是否是 Module 里的 Loader
    var $_ci_module_path = '';  // 当前 Module 所在路径
    var $_ci_module_class = '';  // 当前 Module 的控制器名
    var $_ci_autoload_libraries = array();  // 自动装载的类库名数组
    var $_ci_autoload_models = array();     // 自动装载的模型名数组
    var $_ci_module_uri = '';   // 当前 Module 的调用 URI
    var $_ci_module_method = '';    // 当前 Module 执行的方法
    var $_ci_module_models = array();    // 用于通过模型类名反查属于哪个模块

    /**
     * Constructor
     *
     * Sets the path to the view files and gets the initial output buffering level
     */
    public function __construct()
    {
        parent::__construct();

        log_message('debug', "MY_Loader Class Initialized");
    }

    // --------------------------------------------------------------------

    // Module 中的 Loader 类实例初始化时，自动调用此函数
    public function _ci_module_ready($class_path, $class_name)
    {
        $CI =& get_instance();

        $this->_ci_is_inside_module = true;
        $this->_ci_module_path = $class_path;
        $this->_ci_module_class = $class_name;

        unset($this->_ci_classes);
        $this->_ci_classes = $CI->load->_ci_classes;

        $this->_ci_models = array();
    }

    // --------------------------------------------------------------------

    /**
     * Module Loader
     *
     * This function lets users load and instantiate module.
     *
     * @access  public
     * @param   string  the module uri of the module
     * @return  void
     */
    public function module($module_uri, $vars = array(), $return = FALSE)
    {
        if ($module_uri == '')
        {
            return;
        }

        $module_uri = trim($module_uri, '/');

        $CI =& get_instance();

        $default_controller = $CI->router->default_controller;

        if (strpos($module_uri, '/') === FALSE)
        {
            $path = '';
            // 只有模块名，使用默认控制器和默认方法
            $module = $module_uri;
            $controller = $default_controller;
            $method = 'index';
            $segments = array();
        }
        else
        {
            $segments = explode('/', $module_uri);

            if (file_exists(APPPATH.'modules/'.$segments[0].'/controllers/'.$segments[1].'.php'))
            {
                $path = '';
                $module = $segments[0];
                $controller = $segments[1];
                $method = isset($segments[2]) ? $segments[2] : 'index';
            }
            // 子目录下有模块？
            elseif (is_dir(APPPATH.'modules/'.$segments[0].'/'.$segments[1].'/controllers'))
            {
                // Set the directory and remove it from the segment array
                $path = $segments[0];
                $segments = array_slice($segments, 1);

                if (count($segments) > 0)
                {
                    // 子目录下有模块？
                    if (is_dir(APPPATH.'modules/'.$path.'/'.$segments[0].'/controllers'))
                    {
                        $module = $segments[0];
                        $controller = isset($segments[1]) ? $segments[1] : $default_controller;
                        $method = isset($segments[2]) ? $segments[2] : 'index';
                    }
                }
                else
                {
                    throw new RuntimeException('Unable to locate the module you have specified: '.$path);
                }
            }
            else
            {
                throw new RuntimeException('Unable to locate the module you have specified: '.$module_uri);
            }

            if ($path != '')
            {
                $path = rtrim($path, '/') . '/';
            }
        }

        // 模块名全部小写
        $module = strtolower($module);

        // 必须是类似这样的模块类名：目录_模块名_控制器名_module (如：Account_Message_Home_module)
        $c = str_replace(' ', '_', ucwords(str_replace('_', ' ', $controller)));
        $class_name = str_replace(' ', '_', ucwords(str_replace('/', ' ', $path.$module.' '.$c))) . '_module';

        // Module 的控制器文件的路径
        $controller_path = APPPATH.'modules/'.$path.ucfirst($module).'/controllers/'.$controller.'.php';

        if ( ! file_exists($controller_path))
        {
            throw new RuntimeException('Unable to locate the module you have specified: '.$path.$module.'/controllers/'.$controller.'.php');
        }

        if ( ! class_exists('CI_Module'))
        {
            require_once(APPPATH.'core/Module.php');
        }

        if (!isset($CI->$class_name))
        {
            // 装载 Module 控制器文件
            require_once($controller_path);

            // 实例化 Module 控制器
            $CI->$class_name = new $class_name();

            // 注意：要操作模块里的 loader 类实例
            $CI->$class_name->load->_ci_module_path = $path.$module;
            $CI->$class_name->load->_ci_module_class = $class_name;

            $CI->$class_name->_ci_module_uri = $path.$module.'/'.$controller;
            $CI->$class_name->_ci_module_method = $method;
        }

        $module_load =& $CI->$class_name->load;

        if (strncmp($method, '_', 1) != 0 && in_array(strtolower($method), array_map('strtolower', get_class_methods($class_name))))
        {
            ob_start();

            log_message('debug', 'Module call: '.$class_name.'->'.$method);

            // Call the requested method.
            // Any URI segments present (besides the class/function) will be passed to the method for convenience
            $output = call_user_func_array(array($CI->$class_name, $method), $module_load->_ci_object_to_array($vars));

            if ($return === TRUE)
            {
                $buffer = ob_get_contents();
                @ob_end_clean();

                $result = ($output) ? $output : $buffer;

                return $result;
            }
            else
            {
                if (ob_get_level() > $this->_ci_ob_level + 1)
                {
                    ob_end_flush();
                }
                else
                {
                    $buffer = ob_get_contents();
                    $result = ($output) ? $output : $buffer;
                    $CI->output->append_output($result);
                    @ob_end_clean();
                }
            }
        }
        else
        {
            throw new RuntimeException('Unable to locate the '.$method.' method you have specified: '.$class_name);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Model Loader
     *
     * Loads and instantiates models.
     *
     * @param   string  $model      Model name
     * @param   string  $name       An optional object name to assign to
     * @param   bool    $db_conn    An optional database connection configuration to initialize
     * @return  object
     */
    public function model($model, $name = '', $db_conn = FALSE)
    {
        if (empty($model))
        {
            return $this;
        }
        elseif (is_array($model))
        {
            foreach ($model as $key => $value)
            {
                is_int($key) ? $this->model($value, '', $db_conn) : $this->model($key, $value, $db_conn);
            }

            return $this;
        }

        $path = '';

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($model, '/')) !== FALSE)
        {
            // The path is in front of the last slash
            $path = substr($model, 0, ++$last_slash);

            // And the model name behind it
            $model = substr($model, $last_slash);
        }

        if (empty($name))
        {
            $name = $model;
        }

        if (in_array($name, $this->_ci_models, TRUE))
        {
            return $this;
        }

        $CI =& get_instance();

        $model_paths = $this->_ci_model_paths;

        if ($this->_ci_is_inside_module)
        {
            $module_class_name = $this->_ci_module_class;
            array_unshift($model_paths, APPPATH.'modules/'.ucfirst($this->_ci_module_path).'/');
            $module_model_name = str_replace(' ', '_', ucwords(str_replace('/', ' ', $this->_ci_module_path.' '.$model)));
            if (isset($CI->$module_class_name->$name))
            {
                throw new RuntimeException('The model name you are loading is the name of a resource that is already being used: '.$module_class_name.'.'.$module_model_name);
            }
        }
        else
        {
            if (isset($CI->$name))
            {
                throw new RuntimeException('The model name you are loading is the name of a resource that is already being used: '.$name);
            }
        }

        if ($db_conn !== FALSE && ! class_exists('CI_DB', FALSE))
        {
            if ($db_conn === TRUE)
            {
                $db_conn = '';
            }

            $this->database($db_conn, FALSE, TRUE);
        }

        // Note: All of the code under this condition used to be just:
        //
        //       load_class('Model', 'core');
        //
        //       However, load_class() instantiates classes
        //       to cache them for later use and that prevents
        //       MY_Model from being an abstract class and is
        //       sub-optimal otherwise anyway.
        if ( ! class_exists('CI_Model', FALSE))
        {
            $app_path = APPPATH.'core'.DIRECTORY_SEPARATOR;
            if (file_exists($app_path.'Model.php'))
            {
                require_once($app_path.'Model.php');
                if ( ! class_exists('CI_Model', FALSE))
                {
                    throw new RuntimeException($app_path."Model.php exists, but doesn't declare class CI_Model");
                }
            }
            elseif ( ! class_exists('CI_Model', FALSE))
            {
                require_once(BASEPATH.'core'.DIRECTORY_SEPARATOR.'Model.php');
            }

            $class = config_item('subclass_prefix').'Model';
            if (file_exists($app_path.$class.'.php'))
            {
                require_once($app_path.$class.'.php');
                if ( ! class_exists($class, FALSE))
                {
                    throw new RuntimeException($app_path.$class.".php exists, but doesn't declare class ".$class);
                }
            }
        }

        $model = ucfirst($model);
        if ( ! class_exists($model, FALSE))
        {
            foreach ($model_paths as $model_path_index => $mod_path)
            {
                if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
                {
                    continue;
                }

                require_once($mod_path.'models/'.$path.$model.'.php');


                if ($this->_ci_is_inside_module && $model_path_index == 0)
                {
                    if ( ! class_exists($module_model_name, FALSE))
                    {
                        throw new RuntimeException($mod_path."models/".$path.$module_model_name.".php exists, but doesn't declare class ".$module_model_name);
                    }
                }
                else
                {
                    if ( ! class_exists($model, FALSE))
                    {
                        throw new RuntimeException($mod_path."models/".$path.$model.".php exists, but doesn't declare class ".$model);
                    }
                }

                break;
            }

            if ($this->_ci_is_inside_module && $model_path_index == 0)
            {
                if ( ! class_exists($module_model_name, FALSE))
                {
                    throw new RuntimeException('Unable to locate the model you have specified: '.$module_model_name);
                }
            }
            else
            {
                if ( ! class_exists($model, FALSE))
                {
                    throw new RuntimeException('Unable to locate the model you have specified: '.$model);
                }
            }

        }
        elseif ( ! is_subclass_of($model, 'CI_Model'))
        {
            throw new RuntimeException("Class ".$model." already exists and doesn't extend CI_Model");
        }

        $this->_ci_models[] = $name;

        if ($this->_ci_is_inside_module)
        {
            // 一定要放到全局 loader 实例中，否则还是无法查询模型属于哪个模块
            $CI->load->_ci_module_models[$module_model_name] = $module_class_name;

            if ($model_path_index == 0)
            {
                $CI->$module_class_name->$name = new $module_model_name();
            }
            else
            {
                $CI->$module_class_name->$name = new $model();
            }
        }
        else
        {
            $CI->$name = new $model();
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * View Loader
     *
     * Loads "view" files.
     *
     * @param   string  $view   View name
     * @param   array   $vars   An associative array of data
     *              to be extracted for use in the view
     * @param   bool    $return Whether to return the view output
     *              or leave it to the Output class
     * @return  object|string
     */
    public function view($view, $vars = array(), $return = FALSE)

    {
        if ($this->_ci_is_inside_module)
        {
            $ext = pathinfo($view, PATHINFO_EXTENSION);
            $view = ($ext == '') ? $view.'.php' : $view;
            $path = APPPATH.'modules/'.ucfirst($this->_ci_module_path).'/views/'.$view;

            if (file_exists($path))
            {
                return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_path' => $path, '_ci_return' => $return));
            }
            else
            {
                return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
            }
        }
        else
        {
            return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
        }
    }

    // --------------------------------------------------------------------

    /**
     * 取当前 Module 某方法的 URL 地址
     *
     * @access  public
     * @param   string  方法名/参数1/.../参数n
     * @param   string  URL 中要替换的控制器名，为空使用当前控制器名
     * @return  string
     */
    public function module_url($uri, $controller_name = '')
    {
        $CI =& get_instance();
        $class = $this->_ci_module_class;

        $module_uri = trim($CI->$class->_ci_module_uri, '/');

        if (!empty($controller_name))
        {
            $arr = explode('/', $module_uri);
            $arr[count($arr) - 1] = str_replace(array('/', '.'), '', $controller_name);
            $module_uri = implode('/', $arr);
        }

        return $this->config->site_url('module/' . $module_uri . '/' . trim($uri, '/'));
    }

    // --------------------------------------------------------------------

    /**
     * Database Loader
     *
     * @param   mixed   $params     Database configuration options
     * @param   bool    $return     Whether to return the database object
     * @param   bool    $query_builder  Whether to enable Query Builder
     *                  (overrides the configuration setting)
     *
     * @return  object|bool Database object if $return is set to TRUE,
     *                  FALSE on failure, CI_Loader instance in any other case
     */
    public function database($params = '', $return = FALSE, $query_builder = NULL)
    {
        // Grab the super object
        $CI =& get_instance();

        // Do we even need to load the database class?
        if ($return === FALSE && $query_builder === NULL && isset($CI->db) && is_object($CI->db) && ! empty($CI->db->conn_id))
        {
            if ($this->_ci_is_inside_module and isset($CI->db))
            {
                $module_class_name = $this->_ci_module_class;
                $CI->$module_class_name->db =& $CI->db;
            }

            return FALSE;
        }

        require_once(BASEPATH.'database/DB.php');

        if ($return === TRUE)
        {
            return DB($params, $query_builder);
        }

        // Initialize the db variable. Needed to prevent
        // reference errors with some configurations
        $CI->db = '';

        // Load the DB class
        $CI->db =& DB($params, $query_builder);

        if ($this->_ci_is_inside_module)
        {
            $module_class_name = $this->_ci_module_class;
            $CI->$module_class_name->db =& $CI->db;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    // 根据是否在模块中来取超级对象
    private function &get_instance()
    {
        $CI =& get_instance();

        if (!empty($this->_ci_module_path))
        {
            $module_class_name = $this->_ci_module_class;
            return $CI->$module_class_name;
        }
        else
        {
            return $CI;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Load the Database Utilities Class
     *
     * @param   object  $db Database object
     * @param   bool    $return Whether to return the DB Utilities class object or not
     * @return  object
     */
    public function dbutil($db = NULL, $return = FALSE)
    {
        $CI = $this->get_instance();

        if ( ! is_object($db) OR ! ($db instanceof CI_DB))
        {
            class_exists('CI_DB', FALSE) OR $this->database();
            $db =& $CI->db;
        }

        require_once(BASEPATH.'database/DB_utility.php');
        require_once(BASEPATH.'database/drivers/'.$db->dbdriver.'/'.$db->dbdriver.'_utility.php');
        $class = 'CI_DB_'.$db->dbdriver.'_utility';

        if ($return === TRUE)
        {
            return new $class($db);
        }

        $CI->dbutil = new $class($db);

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Load the Database Forge Class
     *
     * @param   object  $db Database object
     * @param   bool    $return Whether to return the DB Forge class object or not
     * @return  object
     */
    public function dbforge($db = NULL, $return = FALSE)
    {
        $CI = $this->get_instance();

        if ( ! is_object($db) OR ! ($db instanceof CI_DB))
        {
            class_exists('CI_DB', FALSE) OR $this->database();
            $db =& $CI->db;
        }

        require_once(BASEPATH.'database/DB_forge.php');
        require_once(BASEPATH.'database/drivers/'.$db->dbdriver.'/'.$db->dbdriver.'_forge.php');

        if ( ! empty($db->subdriver))
        {
            $driver_path = BASEPATH.'database/drivers/'.$db->dbdriver.'/subdrivers/'.$db->dbdriver.'_'.$db->subdriver.'_forge.php';
            if (file_exists($driver_path))
            {
                require_once($driver_path);
                $class = 'CI_DB_'.$db->dbdriver.'_'.$db->subdriver.'_forge';
            }
        }
        else
        {
            $class = 'CI_DB_'.$db->dbdriver.'_forge';
        }

        if ($return === TRUE)
        {
            return new $class($db);
        }

        $CI->dbforge = new $class($db);
        return $this;
    }


    // --------------------------------------------------------------------

    /**
     * Language Loader
     *
     * Loads language files.
     *
     * @param   string|string[] $files  List of language file names to load
     * @param   string      Language name
     * @return  object
     */
    public function language($files, $lang = '')
    {
        $CI = $this->get_instance();

        $CI->lang->load($files, $lang);
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Config Loader
     *
     * Loads a config file (an alias for CI_Config::load()).
     *
     * @uses    CI_Config::load()
     * @param   string  $file           Configuration file name
     * @param   bool    $use_sections       Whether configuration values should be loaded into their own section
     * @param   bool    $fail_gracefully    Whether to just return FALSE or display an error message
     * @return  bool    TRUE if the file was loaded correctly or FALSE on failure
     */
    public function config($file, $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $CI = $this->get_instance();
        return $CI->config->load($file, $use_sections, $fail_gracefully);
    }

    // --------------------------------------------------------------------

    /**
     * Internal CI Data Loader
     *
     * Used to load views and files.
     *
     * Variables are prefixed with _ci_ to avoid symbol collision with
     * variables made available to view files.
     *
     * @used-by CI_Loader::view()
     * @used-by CI_Loader::file()
     * @param   array   $_ci_data   Data to load
     * @return  object
     */
    protected function _ci_load($_ci_data)
    {
        // Set the default data variables
        foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
        {
            $$_ci_val = isset($_ci_data[$_ci_val]) ? $_ci_data[$_ci_val] : FALSE;
        }

        $file_exists = FALSE;

        // Set the path to the requested file
        if (is_string($_ci_path) && $_ci_path !== '')
        {
            $_ci_x = explode('/', $_ci_path);
            $_ci_file = end($_ci_x);
        }
        else
        {
            $_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
            $_ci_file = ($_ci_ext === '') ? $_ci_view.'.php' : $_ci_view;

            foreach ($this->_ci_view_paths as $_ci_view_file => $cascade)
            {
                if (file_exists($_ci_view_file.$_ci_file))
                {
                    $_ci_path = $_ci_view_file.$_ci_file;
                    $file_exists = TRUE;
                    break;
                }

                if ( ! $cascade)
                {
                    break;
                }
            }
        }

        if ( ! $file_exists && ! file_exists($_ci_path))
        {
            show_error('Unable to load the requested file: '.$_ci_file);
        }

        // This allows anything loaded using $this->load (views, files, etc.)
        // to become accessible from within the Controller and Model functions.
        $_ci_CI = $this->get_instance();
        foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
        {
            if ( ! isset($this->$_ci_key))
            {
                $this->$_ci_key =& $_ci_CI->$_ci_key;
            }
        }

        /*
         * Extract and cache variables
         *
         * You can either set variables using the dedicated $this->load->vars()
         * function or via the second parameter of this function. We'll merge
         * the two types and cache them so that views that are embedded within
         * other views can have access to these variables.
         */
        if (is_array($_ci_vars))
        {
            foreach (array_keys($_ci_vars) as $key)
            {
                if (strncmp($key, '_ci_', 4) === 0)
                {
                    unset($_ci_vars[$key]);
                }
            }

            $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
        }
        extract($this->_ci_cached_vars);

        /*
         * Buffer the output
         *
         * We buffer the output for two reasons:
         * 1. Speed. You get a significant speed boost.
         * 2. So that the final rendered template can be post-processed by
         *  the output class. Why do we need post processing? For one thing,
         *  in order to show the elapsed page load time. Unless we can
         *  intercept the content right before it's sent to the browser and
         *  then stop the timer it won't be accurate.
         */
        ob_start();

        // If the PHP installation does not support short tags we'll
        // do a little string replacement, changing the short tags
        // to standard PHP echo statements.
        if ( ! is_php('5.4') && ! ini_get('short_open_tag') && config_item('rewrite_short_tags') === TRUE)
        {
            echo eval('?>'.preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
        }
        else
        {
            include($_ci_path); // include() vs include_once() allows for multiple views with the same name
        }

        log_message('info', 'File loaded: '.$_ci_path);

        // Return the file data if requested
        if ($_ci_return === TRUE)
        {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }

        /*
         * Flush the buffer... or buff the flusher?
         *
         * In order to permit views to be nested within
         * other views, we need to flush the content back out whenever
         * we are beyond the first level of output buffering so that
         * it can be seen and included properly by the first included
         * template and any subsequent ones. Oy!
         */
        if (ob_get_level() > $this->_ci_ob_level + 1)
        {
            ob_end_flush();
        }
        else
        {
            $_ci_CI->output->append_output(ob_get_contents());
            @ob_end_clean();
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Internal CI Library Loader
     *
     * @used-by CI_Loader::library()
     * @uses    CI_Loader::_ci_init_library()
     *
     * @param   string  $class      Class name to load
     * @param   mixed   $params     Optional parameters to pass to the class constructor
     * @param   string  $object_name    Optional object name to assign to
     * @return  void
     */
    protected function _ci_load_library($class, $params = NULL, $object_name = NULL)
    {
        // Get the class name, and while we're at it trim any slashes.
        // The directory path can be included as part of the class name,
        // but we don't want a leading slash
        $class = str_replace('.php', '', trim($class, '/'));

        // Was the path included with the class name?
        // We look for a slash to determine this
        if (($last_slash = strrpos($class, '/')) !== FALSE)
        {
            // Extract the path
            $subdir = substr($class, 0, ++$last_slash);

            // Get the filename from the path
            $class = substr($class, $last_slash);
        }
        else
        {
            $subdir = '';
        }

        $class = ucfirst($class);

        // Is this a stock library? There are a few special conditions if so ...
        if (file_exists(BASEPATH.'libraries/'.$subdir.$class.'.php'))
        {
            return $this->_ci_load_stock_library($class, $subdir, $params, $object_name);
        }

        // Let's search for the requested library file and load it.
        foreach ($this->_ci_library_paths as $path)
        {
            // BASEPATH has already been checked for
            if ($path === BASEPATH)
            {
                continue;
            }

            $filepath = $path.'libraries/'.$subdir.$class.'.php';

            // Safety: Was the class already loaded by a previous call?
            if (class_exists($class, FALSE))
            {
                if (!empty($this->_ci_module_path))
                {
                    $CI = $this->get_instance();
                    if ( ! isset($CI->$class))
                    {
                        return $this->_ci_init_library($class, '', $params, $object_name);
                    }
                }

                // Before we deem this to be a duplicate request, let's see
                // if a custom object name is being supplied. If so, we'll
                // return a new instance of the object
                if ($object_name !== NULL)
                {
                    $CI = $this->get_instance();
                    if ( ! isset($CI->$object_name))
                    {
                        return $this->_ci_init_library($class, '', $params, $object_name);
                    }
                }

                log_message('debug', $class.' class already loaded. Second attempt ignored.');
                return;
            }
            // Does the file exist? No? Bummer...
            elseif ( ! file_exists($filepath))
            {
                continue;
            }

            include_once($filepath);
            return $this->_ci_init_library($class, '', $params, $object_name);
        }

        // One last attempt. Maybe the library is in a subdirectory, but it wasn't specified?
        if ($subdir === '')
        {
            return $this->_ci_load_library($class.'/'.$class, $params, $object_name);
        }

        // If we got this far we were unable to find the requested class.
        log_message('error', 'Unable to load the requested class: '.$class);
        show_error('Unable to load the requested class: '.$class);
    }

    // --------------------------------------------------------------------

    /**
     * Internal CI Stock Library Loader
     *
     * @used-by CI_Loader::_ci_load_library()
     * @uses    CI_Loader::_ci_init_library()
     *
     * @param   string  $library    Library name to load
     * @param   string  $file_path  Path to the library filename, relative to libraries/
     * @param   mixed   $params     Optional parameters to pass to the class constructor
     * @param   string  $object_name    Optional object name to assign to
     * @return  void
     */
    protected function _ci_load_stock_library($library_name, $file_path, $params, $object_name)
    {
        $prefix = 'CI_';

        if (class_exists($prefix.$library_name, FALSE))
        {
            if (class_exists(config_item('subclass_prefix').$library_name, FALSE))
            {
                $prefix = config_item('subclass_prefix');
            }

            // Before we deem this to be a duplicate request, let's see
            // if a custom object name is being supplied. If so, we'll
            // return a new instance of the object
            if ($object_name !== NULL)
            {
                $CI = $this->get_instance();
                if ( ! isset($CI->$object_name))
                {
                    return $this->_ci_init_library($library_name, $prefix, $params, $object_name);
                }
            }

            log_message('debug', $library_name.' class already loaded. Second attempt ignored.');
            return;
        }

        $paths = $this->_ci_library_paths;
        array_pop($paths); // BASEPATH
        array_pop($paths); // APPPATH (needs to be the first path checked)
        array_unshift($paths, APPPATH);

        foreach ($paths as $path)
        {
            if (file_exists($path = $path.'libraries/'.$file_path.$library_name.'.php'))
            {
                // Override
                include_once($path);
                if (class_exists($prefix.$library_name, FALSE))
                {
                    return $this->_ci_init_library($library_name, $prefix, $params, $object_name);
                }
                else
                {
                    log_message('debug', $path.' exists, but does not declare '.$prefix.$library_name);
                }
            }
        }

        include_once(BASEPATH.'libraries/'.$file_path.$library_name.'.php');

        // Check for extensions
        $subclass = config_item('subclass_prefix').$library_name;
        foreach ($paths as $path)
        {
            if (file_exists($path = $path.'libraries/'.$file_path.$subclass.'.php'))
            {
                include_once($path);
                if (class_exists($subclass, FALSE))
                {
                    $prefix = config_item('subclass_prefix');
                    break;
                }
                else
                {
                    log_message('debug', $path.' exists, but does not declare '.$subclass);
                }
            }
        }

        return $this->_ci_init_library($library_name, $prefix, $params, $object_name);
    }

    // --------------------------------------------------------------------

    /**
     * Internal CI Library Instantiator
     *
     * @used-by CI_Loader::_ci_load_stock_library()
     * @used-by CI_Loader::_ci_load_library()
     *
     * @param   string      $class      Class name
     * @param   string      $prefix     Class name prefix
     * @param   array|null|bool $config     Optional configuration to pass to the class constructor:
     *                      FALSE to skip;
     *                      NULL to search in config paths;
     *                      array containing configuration data
     * @param   string      $object_name    Optional object name to assign to
     * @return  void
     */
    protected function _ci_init_library($class, $prefix, $config = FALSE, $object_name = NULL)
    {
        // Is there an associated config file for this class? Note: these should always be lowercase
        if ($config === NULL)
        {
            // Fetch the config paths containing any package paths
            $config_component = $this->_ci_get_component('config');

            if (is_array($config_component->_config_paths))
            {
                $found = FALSE;
                foreach ($config_component->_config_paths as $path)
                {
                    // We test for both uppercase and lowercase, for servers that
                    // are case-sensitive with regard to file names. Load global first,
                    // override with environment next
                    if (file_exists($path.'config/'.strtolower($class).'.php'))
                    {
                        include($path.'config/'.strtolower($class).'.php');
                        $found = TRUE;
                    }
                    elseif (file_exists($path.'config/'.ucfirst(strtolower($class)).'.php'))
                    {
                        include($path.'config/'.ucfirst(strtolower($class)).'.php');
                        $found = TRUE;
                    }

                    if (file_exists($path.'config/'.ENVIRONMENT.'/'.strtolower($class).'.php'))
                    {
                        include($path.'config/'.ENVIRONMENT.'/'.strtolower($class).'.php');
                        $found = TRUE;
                    }
                    elseif (file_exists($path.'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php'))
                    {
                        include($path.'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php');
                        $found = TRUE;
                    }

                    // Break on the first found configuration, thus package
                    // files are not overridden by default paths
                    if ($found === TRUE)
                    {
                        break;
                    }
                }
            }
        }

        $class_name = $prefix.$class;

        // Is the class name valid?
        if ( ! class_exists($class_name, FALSE))
        {
            log_message('error', 'Non-existent class: '.$class_name);
            show_error('Non-existent class: '.$class_name);
        }

        // Set the variable name we will assign the class to
        // Was a custom class name supplied? If so we'll use it
        if (empty($object_name))
        {
            $object_name = strtolower($class);
            if (isset($this->_ci_varmap[$object_name]))
            {
                $object_name = $this->_ci_varmap[$object_name];
            }
        }

        // Don't overwrite existing properties
        $CI = $this->get_instance();
        if (isset($CI->$object_name))
        {
            if ($CI->$object_name instanceof $class_name)
            {
                log_message('debug', $class_name." has already been instantiated as '".$object_name."'. Second attempt aborted.");
                return;
            }

            show_error("Resource '".$object_name."' already exists and is not a ".$class_name." instance.");
        }

        // Save the class name and object name
        $this->_ci_classes[$object_name] = $class;

        // Instantiate the class
        $CI->$object_name = isset($config)
            ? new $class_name($config)
            : new $class_name();
    }

    // --------------------------------------------------------------------

    /**
     * CI Component getter
     *
     * Get a reference to a specific library or model.
     *
     * @param   string  $component  Component name
     * @return  bool
     */
    protected function &_ci_get_component($component)
    {
        $CI = $this->get_instance();

        return $CI->$component;
    }

    // --------------------------------------------------------------------

    // 获取 _base_classes 属性
    public function get_base_classes()
    {
        return $this->_ci_classes;
    }
}

/* End of file MY_Loader.php */
/* Location: ./application/core/MY_Loader.php */
