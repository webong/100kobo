<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_billboard');
	}
	
	public function index()
	{
		echo $this->m_billboard->check_all();
	}
}