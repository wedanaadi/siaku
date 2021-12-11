<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
		$data['title'] = 'judul';
		$this->load->view('test',$data);
	}
}
