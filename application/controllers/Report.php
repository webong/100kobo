<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_reports');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in'))
		{
			$this->load->library('form_validation');

			$this->form_validation->set_rules('user_id', 'User', 'required|trim');
			$this->form_validation->set_rules('message', 'Message', 'required|trim');

			if ($this->form_validation->run())
			{
				$this->m_reports->report_user(
					$this->session->userdata('user_id'),
					$this->input->post('user_id'),
					$this->input->post('message')
				);

				$this->session->set_flashdata('message_s','User reported');
			} else {
				foreach($this->form_validation->error_array() as $error) {
					$this->session->set_flashdata('message_f', $error);
					break;
				}
			}
		}
		else
		{
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
		}

		redirect('/');
	}
}