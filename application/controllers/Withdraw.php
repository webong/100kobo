<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdraw extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_posts');
		$this->load->model('m_gallery');
		$this->load->model('m_wallet');
		$this->load->model('m_withdraws');
		$this->load->model('m_billboard');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('access') == 3) {
				$header['title'] = 'Withdraw requests';
				$header['access'] = $this->session->userdata('access');
				$header['user'] = $this->session->userdata('username');
				$header['user'] = $this->m_users->get_user_info($this->session->userdata('user_id'));

				$data_1['user'] = $header['user'];
				$data_1['gallery'] = $this->m_posts->get_images_from_user($this->session->userdata('user_id'));
				$data_1['gallery_price'] = $this->m_gallery->get_gallery_price($this->session->userdata('user_id')) / 100;

				$data_2['withdraws_nonsolved'] = $this->m_withdraws->get_withdraws(0);

				foreach ($data_2['withdraws_nonsolved'] as $withdraw) {
					$withdraw->username = $this->m_users->get_user_info($withdraw->user_id)->username;
					$withdraw->added = $this->time_elapsed_string($withdraw->added);
				}

				$data_2['withdraws_solved'] = $this->m_withdraws->get_withdraws(1);

				foreach ($data_2['withdraws_solved'] as $withdraw) {
					$withdraw->username 	= $this->m_users->get_user_info($withdraw->user_id)->username;
					$withdraw->added = $this->time_elapsed_string($withdraw->added);
					$withdraw->solved_date 	= $this->time_elapsed_string($withdraw->solved_date);
				}

				$this->load->view('home/header', $header);
				$this->load->view('withdraw/content', $data_2);
				$this->load->view('home/footer');

			} else {
				$this->session->set_flashdata('message_f', 'You don\'t have privileges to access this page');
				$this->redirect_back();
			}

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}

	public function process()
	{
		if ($this->session->userdata('is_logged_in')) {

			$this->load->library('form_validation');

			$this->form_validation->set_rules('id', 'Withdraw ID', 'required|trim|is_numeric');
			$this->form_validation->set_rules('user_id', 'User ID', 'required|trim|is_numeric');
			$this->form_validation->set_rules('mark_as', 'Withdraw State', 'required|trim');

			if ($this->form_validation->run()) {

				$user_id = $this->input->post('user_id');
				$withdraw_id = $this->input->post('id');

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
				$this->email->to($this->m_users->get_user_info($user_id)->email);
				$this->email->subject("Withdraw request");

				if ($this->input->post('mark_as') == 'approved') {
					if ($this->m_withdraws->mark_as_solved($withdraw_id, $user_id, 1)) {

						// Send email
						$message = "<p>Your withdraw request has been approved</p>";
						$message .= "<p>If you do not remember requesting a withdraw please contact us <b>immediately</b>.</p>";
						$this->email->message($message);
						$this->email->send();

						$this->session->set_flashdata('message_s', 'Withdraw request resolved');
					}
				} elseif ($this->input->post('mark_as') == 'declined') {
					if ($this->m_withdraws->mark_as_solved($withdraw_id, $user_id, 2)) {

						// Send email
						$message = "<p>Your withdraw request has been declined</p>";
						$message .= "<p>If you do not remember requesting a withdraw please contact us <b>immediately</b></p>";
						$this->email->message($message);
						$this->email->send();

						// Refund money to user
						$withdraw = $this->m_withdraws->get_withdraw($withdraw_id);

						// Add amount to user
						$this->m_wallet->add_amount(0, $user_id, $withdraw->amount, 'wallet', 5);

						$this->session->set_flashdata('message_s', 'Withdraw request resolved');
					}
				}
			}

			redirect('/withdraw');

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
		}

		redirect('/');
	}
}