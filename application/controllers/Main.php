<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){

	}

	/*
	 * 方法请求 - url
	 */
	public function action() {
        header('content-type:application/json;charset=utf8');
		$modules_name   = $this->uri->segment(3);
		$modules_action = $this->uri->segment(4);
		$modules_method = $this->uri->segment(5);
		$data = $_GET;

		if ( is_array($data)< 1 ) {
			$data = $_POST;
		}

		$this->load->module("{$modules_name}/{$modules_action}/{$modules_method}",$data,true);
	}

}
