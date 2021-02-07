<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module 代理控制器
 *
 * 用于转发 Module 的请求
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		Hex
 * @category	HMVC
 * @link		http://codeigniter.org.cn/forums/thread-1319-1-2.html
 */

class Module_proxy extends CI_Controller {

	function _remap($method)
	{
		$total = $this->uri->total_rsegments();

		if ($total < 1)
		{
			show_404('Module Not Found.');

			return;
		}

		$param = array();
		$segments = $this->uri->rsegment_array();
		$uri = implode($segments, '/');

		// 判断是否请求的子目录下的模块
		if ($total > 1 && is_dir(APPPATH.'modules/'.$segments[0].'/'.$segments[1].'/controllers'))
		{
			if ($total > 4)
			{
				$param = array_slice($segments, 4);
				$uri = implode(array_slice($segments, 0, 4), '/');
			}
		}
		else
		{
			if ($total > 3)
			{
				$param = array_slice($segments, 3);
				$uri = implode(array_slice($segments, 0, 3), '/');
			}
		}

		// 直接转发给相应的 Module 处理
		$this->load->module($uri, $param);
	}

	function index()
	{
		$this->_remap('index');
	}

}

/* End of file Module_proxy.php */
/* Location: ./application/third_party/Module_proxy.php */
