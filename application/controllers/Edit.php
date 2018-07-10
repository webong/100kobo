<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_posts');
		$this->load->model('m_users');
		$this->load->model('m_billboard');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {

			$this->load->library('form_validation');
			$this->load->helper('string');

			$this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('reply_id', '', 'trim');
			$this->form_validation->set_rules('post_id', '', 'trim');

			$config['upload_path'] 		= './images/';
			$config['allowed_types']	= 'gif|jpg|png';
			$config['max_size']			= '2048';
			$config['max_width'] 		= '1920';
			$config['max_height'] 		= '1080';
			$config['file_name'] 		= random_string('alnum', 8).'_'.random_string('alnum', 8);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$image = null;

			if ($this->upload->do_upload('file') === null) {
				if ("You did not select a file to upload." != $this->upload->display_errors('','')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('message_f', $error['error']);
					$this->redirect_back();
				}
			} else {
				$image = $this->upload->data()['file_name'];
			}

			if ($this->form_validation->run())
			{
				if ($this->m_posts->user_has_post($this->input->post('post_id'))) {
					if ($this->m_posts->update_comment($this->input->post('post_id'), $this->no_xss($this->input->post('message')), $image)) {
						$this->session->set_flashdata('message_s','Updated successfully');
					} else {
						$this->session->set_flashdata('message_f', 'Something went wrong');
					}
				} else {
					$this->session->set_flashdata('message_f', 'It\'s not your post');
				}
			} else {
				foreach($this->form_validation->error_array() as $error) {
					$this->session->set_flashdata('message_f', $error);
					break;
				}
			}

			$this->redirect_back();

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}

	public function add_billboard()
	{
		$post_id = $this->uri->segment(3);

		if ($this->m_posts->user_has_post($post_id)) {
			if ($this->activate($post_id)) {
				$this->session->set_flashdata('message_s','Added to billboard');
			} else {
				// $this->session->set_flashdata('message_f','Can\'t add to billboard. No time left');
				$this->m_billboard->reset_billboard($post_id);
				$this->session->set_flashdata('message_s','Added to billboard');
			}
		}

		$this->redirect_back();
	}

	public function remove_billboard()
	{
		$post_id = $this->uri->segment(3);

		if ($this->m_posts->user_has_post($post_id)) {
			if ($this->deactivate($post_id)) {
				$this->session->set_flashdata('message_s','Removed from billboard');
			} else {
				$this->session->set_flashdata('message_f','Something went wrong');
			}
		}

		$this->redirect_back();
	}

	public function change_profile_img()
	{
		if ($this->session->userdata('is_logged_in')) {

			$this->load->library('form_validation');
			$this->load->helper('string');

			$config['upload_path'] 		= './images/';
			$config['allowed_types']	= 'gif|jpg|png';
			$config['max_size']			= '2048';
			$config['max_width'] 		= '1920';
			$config['max_height'] 		= '1080';
			$config['file_name'] 		= random_string('alnum', 8).'_'.random_string('alnum', 8);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$image = null;

			if ($this->upload->do_upload('file') === null) {
				if ("You did not select a file to upload." != $this->upload->display_errors('','')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('message_f', $error['error']);
				}
			} else {
				$image = $this->upload->data()['file_name'];
				$this->m_users->update_profile_image($image);

				$this->session->set_flashdata('message_s','Profile picture updated successfully');
			}

			$this->redirect_back();

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}

	private function activate($post_id)
	{
		// Add image to billboard
		if (!$this->m_billboard->check_if_exist($post_id)) {
			// If this is first activating create row in database
			if ($this->m_billboard->add_image($post_id))
				return true;

		} else {
			// If image already exist in database
			if ($this->m_billboard->check_if_can_activate($post_id))
			{
				// Mark as active on billboard
				if ($this->m_billboard->mark_active($post_id))
					return true;

			} else {
				// No time left for billboard
				$this->m_billboard->mark_finished($post_id);
			}
		}
		return false;
	}

	private function deactivate($post_id)
	{
		if ($this->m_billboard->mark_inactive($post_id)) {
			return true;
		} else {
			return false;
		}
	}
}