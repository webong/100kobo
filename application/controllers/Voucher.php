<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends MY_Controller {

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
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('access') == 3) {
				$header['title'] = 'Voucher For Cash / Become Paid User';
				$header['access'] = $this->session->userdata('access');
				$header['user'] = $this->session->userdata('username');
				$header['user'] = $this->m_users->get_user_info($this->session->userdata('user_id'));

				$data_1['user'] = $header['user'];
				$data_1['gallery'] = $this->m_posts->get_images_from_user($this->session->userdata('user_id'));
				$data_1['gallery_price'] = $this->m_gallery->get_gallery_price($this->session->userdata('user_id')) / 100;

				$data_2['voucher_requests'] = $this->m_voucher->get_all('pending');

				foreach ($data_2['voucher_requests'] as $request) {
					$request->username = $this->m_users->get_user_info($request->user_id)->username;
					$request->added = $this->time_elapsed_string($request->added);
				}

				$data_2['voucher_requests_validated'] = $this->m_voucher->get_all('valid');

				foreach ($data_2['voucher_requests_validated'] as $request) {
					$request->username = $this->m_users->get_user_info($request->user_id)->username;
					$request->added = $this->time_elapsed_string($request->added);
				}

				$data_2['voucher_requests_paid'] = $this->m_voucher->get_all('paid');

				foreach ($data_2['voucher_requests_paid'] as $request) {
					$request->username = $this->m_users->get_user_info($request->user_id)->username;
					$request->added = $this->time_elapsed_string($request->added);
				}

				$data_2['voucher_requests_declined'] = $this->m_voucher->get_all('wpin');

				foreach ($data_2['voucher_requests_declined'] as $request) {
					$request->username = $this->m_users->get_user_info($request->user_id)->username;
					$request->added = $this->time_elapsed_string($request->added);
				}

				$this->load->view('home/header', $header);
				$this->load->view('voucher/content', $data_2);
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

	
	public function add()
	{
		if ($this->session->userdata('is_logged_in')) {

			$this->load->library('form_validation');

			$this->form_validation->set_rules('v_type', 'Voucher Type', 'required|trim|callback_voucher_type');
			$this->form_validation->set_rules('amount', 'Amount', 'required|trim|is_numeric|callback_minimum_amount');
			$this->form_validation->set_rules('voucher', 'Voucher', 'required|trim');
			$this->form_validation->set_rules('product', 'Product Type', 'required|trim');

			// $amount = $this->input->post('amount') * 0.81;

			if ($this->form_validation->run()) {
				if ($this->m_voucher->add($this->session->userdata('user_id'), $this->input->post('v_type'), $this->input->post('product'), $this->input->post('amount'), $this->input->post('voucher'))) {
					$this->session->set_flashdata('message_s','Voucher added successfully');
				} else {
					$this->session->set_flashdata('message_f', 'Voucher not added');
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

		$this->redirect_back();
	}

	public function voucher_type($value)
	{
		if ($value === 'mtn' || $value === 'glo') 
			return true;
		else {
			$this->form_validation->set_message('voucher_type', 'Voucher type must be GLO or MTN');
			return false;
		}
	}

	public function minimum_amount($value)
	{
		if ($this->input->post('product') == 'vfc') {
			if ($value >= 1000) {
		        return true;
		    }
		    else {
		        $this->form_validation->set_message('minimum_amount', 'Amount must be at least N1,000.00');
		        return false;
		    }
		} elseif ($this->input->post('product') == 'topu') {
			if ($value >= 100) {
		        return true;
		    }
		    else {
		        $this->form_validation->set_message('minimum_amount', 'Amount must be at least N100.00');
		        return false;
		    }
		} else {
			if ($value == 10000) {
		        return true;
		    }
		    else {
		        $this->form_validation->set_message('minimum_amount', 'Amount must be N100.00');
		        return false;
		    }
		}
	}

	public function process()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('access') == 3) {
				
				$this->load->library('form_validation');

				$this->form_validation->set_rules('id', 'ID', 'required|trim|is_numeric');
				$this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
				$this->form_validation->set_rules('mark_as', 'Status', 'required|trim');
				$this->form_validation->set_rules('amount', 'Amount', 'required|trim');
				$this->form_validation->set_rules('p_type', 'Product Type', 'required|trim');

				if ($this->form_validation->run()) {
					if ($this->input->post('mark_as') == 'valid' || $this->input->post('mark_as') == 'wpin' || $this->input->post('mark_as') == 'paid') {

						$user_id = $this->input->post('user_id');
						$amount = $this->input->post('amount');

						// If it's paid add amount to user
						if ($this->input->post('mark_as') == 'valid') {

							$this->m_voucher->resolve($this->input->post('id'), $this->input->post('mark_as'));
						
							$this->session->set_flashdata('message_s','Voucher marked as validated');

						} elseif ($this->input->post('mark_as') == 'wpin') {
							
							$this->m_voucher->resolve($this->input->post('id'), $this->input->post('mark_as'));
							
							$this->session->set_flashdata('message_s','Voucher marked as invalid');
							
						} elseif ($this->input->post('mark_as') == 'paid') {

							// Mark it as paid
							$this->m_voucher->resolve($this->input->post('id'), 'paid');

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

							$email = $this->m_users->get_user_info($user_id)->email;
							$this->email->to($email);

							if ($this->input->post('p_type') == 'topu') {
								// Add top-up coins to user
								$this->m_wallet->add_amount(0, $user_id, $amount, 'wallet_topup', 4);

								$n_amount = 'N'.$amount/100;
								$this->email->subject("You have added $n_amount");
								$message = "<p>Congratulations! Your voucher has been validated and you have added $n_amount to your <strong>Top-up wallet</strong>.</p>";
								$this->email->message($message);

								$this->email->send();
							}
							elseif ($this->input->post('p_type') == 'paid')
							{
								// Check if user has certan amount to be decudeted from his wallet
								if ($this->m_wallet->check_if_user_has_amount($user_id, 100, 'wallet_free')) {
									$wallet = 'wallet_free';
								} elseif ($this->m_wallet->check_if_user_has_amount($user_id, 100, 'wallet')) {
									$wallet = 'wallet';
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


									$this->email->subject("You are now a Paid user");
									$message = "<p>Congratulations! Your voucher has been validated and you are now a <strong>paid</strong> user.</p>";
									$this->email->message($message);

									$this->email->send();

								}  else {
									$this->session->set_flashdata('message_f', 'Not enought money in user\'s wallet');
								}
							}
							elseif ($this->input->post('p_type') == 'vfc')
							{
								// Add coins to user
								$this->m_wallet->add_amount(0, $user_id, $amount, 'wallet', 6);

								$n_amount = 'N'.$amount/100;
								$this->email->subject("You have added $n_amount");
								$message = "<p>Congratulations! Your voucher has been validated and you have added $n_amount to your <strong>Wallet</strong>.</p>";
								$this->email->message($message);

								$this->email->send();
							}

							$this->session->set_flashdata('message_s','Voucher marked as paid');

						} else {
							$this->session->set_flashdata('message_f', 'Voucher not resolved');
						}

					} else {
						$this->session->set_flashdata('message_f', 'Voucher not resolved');
					}

				} else {
					foreach($this->form_validation->error_array() as $error) {
						$this->session->set_flashdata('message_f', $error);
						break;
					}
				}

			} else {
				$this->session->set_flashdata('message_f', 'You don\'t have privileges to access this page');
			}

			$this->redirect_back();

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}
}