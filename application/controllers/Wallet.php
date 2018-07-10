<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_posts');
		$this->load->model('m_wallet');
		$this->load->model('m_voucher');
		$this->load->model('m_withdraws');
		$this->load->model('m_billboard');
	}
	
	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {
			if ($this->session->userdata('access') > 0) {
				if ($this->session->userdata('not_updated')) {
					$this->session->set_flashdata('message_i', 'Add your first name, surname and phone number in <a href="'.base_url().'settings">Settings</a>');
				} else {
					$header['updated'] = 1;
				}

				$user_id = $this->session->userdata('user_id');

				$header['title'] = 'Wallet';
				$header['access'] = $this->session->userdata('access');
				$header['user'] = $this->session->userdata('username');
				$header['user'] = $this->m_users->get_user_info($user_id);

				$data_1['user'] = $header['user'];
				$data_1['gallery'] = $this->m_posts->get_images_from_user($user_id);

				$data_2['user'] = $header['user'];
				$data_2['wallet'] = $this->m_wallet->calculate_wallet($user_id, 'wallet') / 100;
				$data_2['wallet_free'] = $this->m_wallet->calculate_wallet($user_id, 'wallet_free') / 100;
				$data_2['wallet_topup'] = $this->m_wallet->calculate_wallet($user_id, 'wallet_topup') / 100;
				$data_2['wallet_history'] = $this->m_wallet->history($user_id);
				$data_2['can_withdraw'] = $this->m_withdraws->can_withdraw($user_id);
				if ($this->session->userdata('access') > 1) {
					$data_2['is_paid'] = 1;
				} else {
					$data_2['is_paid'] = 0;
				}

				foreach ($data_2['wallet_history'] as $trans) {
					// Check if user was getting coins or giving
					if ($trans->user_from == $user_id) {
						$trans->whom = 'from';
					} else {
						$trans->whom = 'to';
					}

					$trans->added = $this->time_elapsed_string($trans->added);

					// Name users by ID
					$trans->user_from 	= $this->m_users->get_user_info($trans->user_from)->username;
					$trans->user_to 	= $this->m_users->get_user_info($trans->user_to)->username;
				}

				$data_3['is_logged_in'] = 1;
				$data_3['access'] = $this->session->userdata('access');
				$data_3['gallery'] = $this->m_posts->list_billboard();

				if ($data_3['trending'] = $this->m_posts->list_trending(10))
				{
					foreach ($data_3['trending'] as $post) {
						$temp_user = new stdClass();
						$temp_user = $this->m_users->get_user_info($post->user_id);
						$post->username = $temp_user->username;
						$post->first_name = $temp_user->first_name;
						$post->surname = $temp_user->surname;
						$post->profile_image = $temp_user->image;
						$post->time_ago = $this->time_elapsed_string($post->posted);
						$post->comment_worth = $this->post_worth_trending($post->id) / 100;
						if ($this->m_billboard->check_if_active($post->id)) $post->billboard = 1;
						$post->comments = count($this->m_posts->get_sub_posts($post->id));
					}
				}

				$header['description'] = '100kobo is a social network where users earn money with their contents.';
				$header['site_name'] = '100kobo';
				$header['url'] = site_url().'wallet';

				$this->load->view('home/header', $header);
				$this->load->view('home/left-container', $data_1);
				$this->load->view('wallet/content', $data_2);
				$this->load->view('home/right-container', $data_3);
				$this->load->view('home/footer');

			} else {
				$this->session->set_flashdata('message_f', 'You have to be a <span data-toggle="modal" data-target="#modal-paid-user-2">paid user</span> in order to access My Wallet.');
				redirect('/');
			}

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}

	public function process()
	{
		if ($this->session->userdata('is_logged_in')) {

			$user_id = $this->session->userdata('user_id');
			$amount = $this->m_wallet->calculate_wallet($user_id, 'wallet');

			if ($this->m_withdraws->can_withdraw($user_id))
			{
				if ($this->m_withdraws->add_withdraw($user_id, $amount)) {

					// Deduct from user all coins in order to withdraw
					$this->m_wallet->deduct_amount($user_id, $amount, 'wallet');

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

					// Send email
					$message = "<p>You have requested a withdraw</p>";
					$message .= "<p> If you do not remember requesting a withdraw please contact us <b>immediately</b>.</p>";
					$this->email->message($message);
					$this->email->send();

					$this->session->set_flashdata('message_s','Success! Your withdraw request has been sent');

				} else {
					$this->session->set_flashdata('message_f', 'Error sending withdraw request');
				}

			} else {
				$this->session->set_flashdata('message_f', 'Not enough funds to refund. Recharge with Glo or MTN');
			}

			redirect('/wallet');

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
		}

		redirect('');
	}

	public function update_details()
	{
		if ($this->session->userdata('is_logged_in')) {

			$this->load->library('form_validation');

			$this->form_validation->set_rules('account_name', 'Account Name', 'required|trim');
			$this->form_validation->set_rules('bank_name', 'Bank Name', 'required|trim');
			$this->form_validation->set_rules('account_number', 'Account Number', 'required|trim');
			$this->form_validation->set_rules('sort_code', 'Sort Code', 'trim');

			if ($this->form_validation->run()) {
				if ($this->m_users->update_bank(
						$this->no_xss($this->input->post('account_name')),
						$this->no_xss($this->input->post('bank_name')),
						$this->no_xss($this->input->post('account_number')),
						$this->no_xss($this->input->post('sort_code'))
					)) {
					$this->session->set_flashdata('message_s','Wallet details updated');
				}
			} else {
				foreach($this->form_validation->error_array() as $error) {
					$this->session->set_flashdata('message_f', $error);
					redirect('/wallet');
					break;
				}
			}

			redirect('/wallet');

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
		}

		redirect('');
	}

	public function addPaid()
	{
		if ($this->session->userdata('is_logged_in')) {

			$this->load->library('form_validation');

			$this->form_validation->set_rules('id', 'ID', 'required|trim');

			if ($this->form_validation->run()) {

				$user_id = $this->session->userdata('user_id');
				// Load email library
				$this->load->library('email');

				// Check if user has certan amount to be decudeted from his wallet
				if ($this->m_wallet->check_if_user_has_amount($user_id, 100, 'wallet_free')) {
					$wallet = 'wallet_free';
				} elseif ($this->m_wallet->check_if_user_has_amount($user_id, 100, 'wallet')) {
					$wallet = 'wallet';
				} elseif ($this->m_wallet->check_if_user_has_amount($user_id, 100, 'wallet_topup')) {
					$wallet = 'wallet_topup';
				}

				if ($wallet) {

					// Mark user as paid user
					$this->m_users->mark_as_paid($user_id);

					$this->m_wallet->deduct_amount($user_id, 10000, $wallet);
				
					// Get 100th MLM user and remove from paid users
					if ($award_user_id = $this->m_users->mlm_N5000()) {

						$username = $this->m_users->get_user_info($award_user_id)->username;
						$this->m_users->admin_remove($username);

						// Add N5000 to wallet
						// $this->m_wallet->add_amount(0, $award_user->user_id, 500000, 'wallet', 9);

						$email = $this->m_users->get_user_info($award_user_id)->email;
						$this->email->to($email);

						$this->email->subject("You are now a regular user");
						$message = "<p>Your paid membership has expired.</p><p>Please fill out the voucher form in order to become paid user again.</p>";
						$this->email->message($message);

						$this->email->send();
					}
					// Pay N50 to 100kobo and N50 to MLM user
					if ($pay_to_user = $this->m_users->mlm_N50()) {

						$this->m_wallet->add_amount($user_id, $pay_to_user, 5000, 'wallet', 9);
						$this->m_wallet->add_amount($user_id, 0, 5000, 'wallet', 9);

					}
					// Add N100 to topup wallet
					// $this->m_wallet->add_amount(0, $user_id, 10000, 'wallet_topup', 9);

					$this->session->set_userdata('access', 2);

					$this->email->subject("You are now a Paid user");
					$message = "<p>Congratulations! You are now a <strong>paid</strong> user.</p>";
					$this->email->message($message);

					$this->email->send();

					$this->session->set_flashdata('message_s', 'Congratulations! You are now a <strong>paid</strong> user');

				}  else {
					$this->session->set_flashdata('message_f', 'Not enought money in user\'s wallet');
				}
			} else {
				foreach($this->form_validation->error_array() as $error) {
					$this->session->set_flashdata('message_f', $error);
					break;
				}
			}
		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
		}

		redirect('/wallet');
	}
}