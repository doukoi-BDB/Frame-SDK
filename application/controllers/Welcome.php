<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index1()
	{
		$this->load->view('welcome_message');
	}
        public function index(){

        $data=array(
            'time'       => date('Y-m-d H:i:s'),
        );

        $this->load->view('main',$data);

    }

}
