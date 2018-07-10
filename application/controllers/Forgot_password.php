<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot_Password extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
	}

	public function index()
	{
		$this->load->library('form_validation');
		$data = array();

		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim');

		if ($this->form_validation->run()) {

			if ($password = $this->m_users->resend_password()) {

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
				$this->email->to($this->input->post('email'));
				$this->email->subject("Restore your Password");
				$message = "<p>You requested password restore. Your new password is: ".$password."</p>";
				$message .= "<p>After login, we suggest you to change your auto-generated password.</p>";
				$message .= "<p>Contact our support if this request was not authorized.</p>";
				$this->email->message($message);

				if ($this->email->send()) {
					$this->session->set_flashdata('message_s', 'Check your email for new password');
				} else {
					$this->session->set_flashdata('message_f', 'Failed sending an email');
				}

			} else {
				$this->session->set_flashdata('message_f', 'Invalid username/email');
			}
		} else {
			foreach($this->form_validation->error_array() as $error) {
				$this->session->set_flashdata('message_f', $error);
				break;
			}
		}

		redirect('/');
	}
}