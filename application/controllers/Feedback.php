<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {

			$this->load->library('form_validation');

			$this->form_validation->set_rules('f_message', 'Message', 'required|trim|min_length[1]|max_length[1000]');

			$access = $this->session->userdata('access');

			if ($this->form_validation->run()) {

				$user = $this->m_users->get_user_info($this->session->userdata('user_id'));

				/* Send feedback message to admin */
				// Load email library
				$this->load->library('email');

				// Email setup
				$config = array(
					'protocol'	=> 'mail',
					'mailpath'	=> '/usr/sbin/sendmail',
					'mailtype'	=> 'html',
					'charset'	=> 'iso-8859-1',
					'wordwrap'	=> TRUE
				);

				$this->email->initialize($config);

				// Compose a message (email)
				$this->email->from('noreply@100kobo.net', "100 Kobo");
				$this->email->to('feedback@100kobo.net'); // Admin's email
				$this->email->subject("Feedback submitted by ".$user->username);
				$message = "<p><strong>User:</strong> ".$user->username."</p>";
				$message .= "<p><strong>Message:</strong> ".$this->input->post('f_message')."</p>";
				$this->email->message($message);

				$this->email->send();
				/* End notification */

				$this->session->set_flashdata('message_s','Feedback submitted successfully');

			} else {
				foreach($this->form_validation->error_array() as $error) {
					$this->session->set_flashdata('message_f', $error);
					break;
				}
			}

			$this->redirect_back();
			
		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
		}

		redirect('/');
	}
}