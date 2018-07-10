<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delete extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_posts');
	}

	public function index()
	{
		redirect('/');
	}

	public function post()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->uri->segment(3) != '' && is_numeric($this->uri->segment(3))) {

				if ($this->m_posts->user_has_post($this->uri->segment(3))) {
					if ($this->m_posts->delete_comment($this->uri->segment(3))) {
						$this->session->set_flashdata('message_s','Deleted successfully');
						redirect('/');
					} else {
						$this->session->set_flashdata('message_f', 'Something went wrong');
						redirect('/');
					}
				} else {
					$this->session->set_flashdata('message_f', 'It\'s not your post');
					redirect('/');
				}

			} else {
				$this->session->set_flashdata('message_f', 'Something went wrong');
				redirect('/');
			}

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}
}