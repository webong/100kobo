<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

	private $skip_update_session = false;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_posts');
		$this->load->model('m_gallery');
		$this->load->model('m_wallet');
		$this->load->model('m_billboard');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->skip_update_session == false) {
				if ($this->session->userdata('not_updated')) {
					// Create popup info to change first_name and surname
					$this->session->set_flashdata('message_i', 'Add your first name, surname and phone number');
				} else {
					$header['updated'] = 1;
				}
			} else {
				$header['updated'] = 1;
			}

			$header['title'] = 'Settings';
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
			$header['url'] = site_url().'settings';
			$header['no_index'] = 1;

			$this->load->view('home/header', $header);
			$this->load->view('home/left-container', $data_1);
			$this->load->view('settings/content', $data_1);
			$this->load->view('home/right-container', $data_3);
			$this->load->view('home/footer');

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}

	public function update_password()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('current', 'Current Password', 'required|trim|callback_validate_password');
		$this->form_validation->set_rules('new', 'New Password', 'required|trim');
		$this->form_validation->set_rules('new2', 'New Password (repeat)', 'required|trim|matches[new]|min_length[6]|max_length[20]');

		$this->form_validation->set_message('required', "{field} is required");

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible" role="alert">
			  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

		if ($this->form_validation->run()) {

			$this->m_users->update_password();

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
			$this->email->to($this->m_users->get_user_info($this->session->userdata('user_id'))->email);
			$this->email->subject("Password changed");
			$message = "<p>Your password has been updated</p>";
			$message .= "<p>If you do not remember changing your password contact us <b>immediately</b>.</p>";
			$this->email->message($message);

			$this->email->send();

			$this->session->set_flashdata('message_s','Password updated');

		}  else {
			foreach($this->form_validation->error_array() as $error) {
				$this->session->set_flashdata('message_f', $error);
				break;
			}
		}

		redirect('/settings');
	}

	public function validate_password()
	{
		// Update password in database
		if ($this->m_users->can_change_password()) {
			return true;
		} else {
			$this->form_validation->set_message('validate_password', 'Incorrect password');
			return false;
		}
	}

	public function update_account()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('first_name', 'First name', 'required|trim');
		$this->form_validation->set_rules('surname', 'Surname', 'required|trim');
		$this->form_validation->set_rules('phone', 'Phone number', 'required|trim|is_natural');

		if ($this->form_validation->run()) {

			if ($this->m_users->update_account(
					$this->no_xss($this->input->post('first_name')),
					$this->no_xss($this->input->post('surname')),
					$this->no_xss($this->input->post('phone'))
				)) {
				$this->session->set_flashdata('message_s','Account info updated');

				if ($this->m_users->not_updated($this->session->userdata('username'))) {
					$this->session->set_userdata('not_updated', 1);
				} else {
					$this->session->set_flashdata('message_s','Account info updated');
					$this->session->unset_userdata('not_updated');
					$this->skip_update_session = true;
				}
			}
		} else {
			foreach($this->form_validation->error_array() as $error) {
				$this->session->set_flashdata('message_f', $error);
				break;
			}
		}

		redirect('/settings');
	}

	public function update_about()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('about', 'About Info', 'required|trim|max_length[160]');
		$this->form_validation->set_rules('profile', 'Profile Image', 'trim');

		if ($this->form_validation->run()) {

			if ($this->m_users->update_about($this->no_xss($this->input->post('about')), $this->input->post('profile'))) {
				$this->session->set_flashdata('message_s','Profile updated');
			}
		}

		redirect('/settings');
	}

	public function update_gallery()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('g_price', 'Price', 'required|trim|is_natural');

		if ($this->form_validation->run()) {
			if ($this->input->post('g_price') >= 100 && $this->input->post('g_price') <= 1000) {
				if ($this->m_gallery->add_gallery_price($this->session->userdata('user_id'), $this->input->post('g_price'))) {
					$this->session->set_flashdata('message_s','Gallery price updated');
				} else {
					$this->session->set_flashdata('message_f', 'Error updating gallery price');
				}
			} else {
				$this->session->set_flashdata('message_f', 'Price must be from N1.00 to N10.00');
			}
		}

		redirect('/settings');
	}

	public function unlock_gallery()
	{
		$price = $this->m_gallery->get_gallery_price($this->uri->segment(3));
		$user_id = $this->session->userdata('user_id');

		// Check if user has certan amount to be decudeted from his wallet
		if ($this->m_wallet->check_if_user_has_amount($user_id, $price, 'wallet_topup')) {
			$wallet = 'wallet_topup';
		} elseif ($this->m_wallet->check_if_user_has_amount($user_id, $price, 'wallet_free')) {
			$wallet = 'wallet_free';
		} elseif ($this->m_wallet->check_if_user_has_amount($user_id, $price, 'wallet')) {
			$wallet = 'wallet';
		}

		if ($wallet) {
			if ($this->m_gallery->unlock_gallery($user_id, $this->uri->segment(3))) {
				
				// Deduct money from current user
				$this->m_wallet->deduct_amount($user_id, $price, $wallet);

				// Add money to users
				$this->m_wallet->add_amount($user_id, $this->uri->segment(3), $price, 'wallet', 3);

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

				$email = $this->m_users->get_user_info($this->uri->segment(3))->email;

				$this->email->to($email);
				$this->email->subject("Your gallery has been unlocked");
				$message = "<p>You have earned <strong>$price</strong> kobo from <a href=\"".base_url()."user/".$this->m_users->get_user_info($user_id)->username."\">".$this->m_users->get_user_info($user_id)->username."<a/></strong> for unlocking your gallery.</p>";
				$this->email->message($message);

				$this->email->send();
				/* End notification */

				$this->session->set_flashdata('message_s','Unlocked successfully');
			}
		} else {
			$this->session->set_flashdata('message_f','Not enough funds to unlock user\'s gallery. Recharge with Glo or MTN');
		}

		$this->redirect_back();
	}
}