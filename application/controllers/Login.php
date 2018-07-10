<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_wallet');
	}

	public function index()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'required|trim|callback_validate_credentials');
		$this->form_validation->set_rules('password', 'Password', 'required|md5|trim');

		$this->form_validation->set_message('required', "{field} is required");

		if ($this->form_validation->run()) {

			$user_id = $this->m_users->get_user_id($this->input->post('username'));
			$user_access = $this->m_users->get_user_access($this->input->post('username'));
			$now = date('Y-m-d H:i:s');

			$data = array(
				'user_id'		=> $user_id,
				'username'		=> $this->input->post('username'),
				'is_logged_in'	=>  1,
				'access'		=> $user_access,
				'login_time'	=> $now
			);

			if ($this->m_users->not_updated($this->input->post('username'))) {
				$data['not_updated'] = 1;
			}

			$this->session->set_userdata($data);

		} else {
			/*
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible" role="alert">
			  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
			  	*/
			foreach($this->form_validation->error_array() as $error) {
				$this->session->set_flashdata('message_f', $error);
				break;
			}
		}

		redirect('/');
	}

	public function validate_credentials()
	{
		if ($this->m_users->can_login()) {
			if ($this->m_users->is_banned()) {
				$this->form_validation->set_message('validate_credentials', 'You are banned from this website');
				return false;
			} else {
				return true;
			}
		} else {
			$this->form_validation->set_message('validate_credentials', 'Incorrect username/password');
			return false;
		}
	}
}