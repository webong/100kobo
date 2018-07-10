<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_wallet');
	}

	public function index()
	{
		$this->load->library('form_validation');
		$data = array();

		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]|alpha_dash');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('cemail', 'Email (again)', 'required|trim|matches[email]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('cpassword', 'Password (again)', 'required|trim|matches[password]');
		$this->form_validation->set_message('is_unique', "That {field} already exist in database");
		$this->form_validation->set_message('matches', "{field}s does not match");
		$this->form_validation->set_message('required', "{field} is required");

		if ($this->form_validation->run()) {
			// Generate a random key
			$key = md5(uniqid());

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
			$this->email->subject("Confirm your account");
			$message = "<p>Thank you for signing up!</p>";
			$message .= "<p><a href='".base_url()."signup/confirm/$key'>Click here</a> to confirm your account.</p>";
			$this->email->message($message);

			if ($this->m_users->add_temp_user($key)) {
				if ($this->email->send()) {
					$this->session->set_flashdata('message_s', 'Email has been sent!');
				} else {
					$this->session->set_flashdata('message_f', 'Failed sending an email');
				}
			}
		} else {
			foreach($this->form_validation->error_array() as $error) {
				$this->session->set_flashdata('message_f', $error);
				break;
			}
		}

		redirect('/');
	}

	public function confirm($key)
	{
		$data = array();

		// Check if validation key is valid
		if ($this->m_users->is_key_valid($key)) {
			if ($this->m_users->confirm_user($key)) {
				$this->session->set_flashdata('message_s', 'Successfully confirmed! Now update your info');

				$user = $this->m_users->get_user_by_key($key);
				$user_access = $this->m_users->get_user_access($user->username);

				$data = array(
					'user_id'		=> $user->id,
					'username'		=> $user->username,
					'is_logged_in'	=>  1,
					'not_updated'	=> 1,
					'access'		=> $user_access
				);

				$this->session->set_userdata($data);

				// Create wallets for user
				$this->m_wallet->create_wallets($user->id);

				// Add 1000 kobo
				$this->m_wallet->add_amount(0, $user->id, 1000, 'wallet_free', 1);

				/* Send notification to user */
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

				$email = $user->email;

				$this->email->to($email);
				$this->email->subject("Your have confirmed you account");
				$message = "<p>You have earned <strong>1000</strong> kobo for confirming your account.</p>";
				$this->email->message($message);
				
				$this->email->send();
				/* End notification */

				redirect('/settings');

			} else {
				$this->session->set_flashdata('message_f', 'Failed confirming your identity');
				redirect('/');
			}
		} else {
			$this->session->set_flashdata('message_f', 'Invalid validation key');
			redirect('/');
		}
	}
}