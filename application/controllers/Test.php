<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_posts');
		$this->load->model('m_gallery');
		$this->load->model('m_wallet');
		$this->load->model('m_voucher');
		$this->load->model('m_billboard');
	}

	public function index()
	{
		$count_paid = $this->m_users->count_paid();

		if ($standard_user_id = $this->m_users->mlm_N50()) {
			$username = $this->m_users->get_user_info($standard_user_id)->username;
			echo 'Standard MLM: ' . $username . '<br />';
		}

		if ($award_user_id = $this->m_users->mlm_N5000()) {
			$username = $this->m_users->get_user_info($award_user_id)->username;
			echo 'Drop MLM: ' . $username . '<br />';
		} else {
			echo 'Drop MLM: ';
		}

		echo 'Paid users: ' . $count_paid;
	}
}