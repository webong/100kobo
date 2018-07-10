<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paid extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_posts');
		$this->load->model('m_users');
		$this->load->model('m_gallery');
		$this->load->model('m_wallet');
		$this->load->model('m_billboard');
		$this->load->model('m_paystack');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('not_updated')) {
				$this->session->set_flashdata('message_i', 'Add your first name, surname and phone number');
				redirect('/settings');
			} else {
				$header['updated'] = 1;
			}

			$header['title'] = 'Fund your wallet';
			$header['access'] = $this->session->userdata('access');
			$header['user'] = $this->session->userdata('username');
			$header['user'] = $this->m_users->get_user_info($this->session->userdata('user_id'));

			$data_1['user'] = $header['user'];
			$data_1['gallery'] = $this->m_posts->get_images_from_user($this->session->userdata('user_id'));
			$data_1['gallery_price'] = $this->m_gallery->get_gallery_price($this->session->userdata('user_id')) / 100;

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
					if ($this->m_billboard->check_if_active($post->id)) $post->billboard = 1;
					$post->comments = count($this->m_posts->get_sub_posts($post->id));
				}
			}

			$header['description'] = '100kobo is a social network where users earn money with their contents.';
			$header['site_name'] = '100kobo';
			$header['url'] = site_url().'topup';

			$this->load->view('home/header', $header);
			$this->load->view('home/left-container', $data_1);
			$this->load->view('special/topup');
			$this->load->view('home/right-container', $data_3);
			$this->load->view('home/footer');

		} else {

			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}

	public function add()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('amount', 'Amount', 'required|trim|is_natural_no_zero');

		if ($this->form_validation->run()) {

			$this->load->library('paystack');
			$amount = $this->input->post('amount') * 100;

		    $reference = $this->session->userdata('user_id') . '_' . $this->generateRandomName(9);

		    $response = $this->paystack->transaction->initialize([
	            'reference'	 => $reference,
	            'amount'	 => $amount,
	            'email'		 => $this->m_users->get_user_info($this->session->userdata('user_id'))->email
	      	]);

	      	if ($response->message == 'Authorization URL created') {

	      		echo 'Redirecting to Paystack...';
	      		$this->m_paystack->add($this->session->userdata('user_id'), $amount, $reference);
	      		redirect($response->data->authorization_url);
	      		exit;

	      	} else {
	      		$this->session->set_flashdata('message_f', $response->message);
	      	}

		} else {
			foreach($this->form_validation->error_array() as $error) {
				$this->session->set_flashdata('message_f', $error);
				break;
			}
		}

		redirect('/topup');
	}

	public function validate()
	{
		$this->load->library('paystack');

		$reference = $this->input->get('reference');

		$response = $this->paystack->transaction->verify([
			'reference' => $reference
		]);

		if ($response->message == 'Verification successful') {

			$this->load->model('m_paystack');

			$this->m_paystack->markAsDone($reference);
			$data = $this->m_paystack->getData($reference);

			// Add top-up coins to user
			$this->m_wallet->add_amount(0, $data->user_id, $data->amount, 'wallet_topup', 4);

			$this->session->set_flashdata('message_s','Success! You have added '.$data->amount.' kobo to your account');

		} else {
      		$this->session->set_flashdata('message_f', $response->message);
      	}

		redirect('/topup');
	}
}