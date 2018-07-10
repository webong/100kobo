<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	private $role = -1;

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
				$header['title'] = 'User Management';
				$header['access'] = $this->session->userdata('access');
				$header['user'] = $this->session->userdata('username');
				$header['user'] = $this->m_users->get_user_info($this->session->userdata('user_id'));

				$data_1['user'] = $header['user'];
				$data_1['gallery'] = $this->m_posts->get_images_from_user($this->session->userdata('user_id'));
				$data_1['gallery_price'] = $this->m_gallery->get_gallery_price($this->session->userdata('user_id')) / 100;

				$data_2['users'] = $this->m_users->get_all_users($this->role);
				$data_2['role'] = $this->role;

				foreach ($data_2['users'] as $user) {
					$user->added = $this->time_elapsed_string($user->joined);
				}

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

				$this->load->view('home/header', $header);
				$this->load->view('users/content', $data_2);
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

	public function admins()
	{
		$this->role = 3;
		$this->index();
	}

	public function paid()
	{
		$this->role = 2;
		$this->index();
	}

	public function regular()
	{
		$this->role = 1;
		$this->index();
	}

	public function banned()
	{
		$this->role = 0;
		$this->index();
	}

	public function add_remove_admin()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('access') == 3) {

				$this->load->library('form_validation');

				$this->form_validation->set_rules('username', 'Username', 'required|trim');
				$this->form_validation->set_rules('action', 'Action', 'required|trim');

				if ($this->form_validation->run()) {
					if ($this->m_users->get_user_id($this->input->post('username'))) {
						if ($this->input->post('action') == 'add') {
							if ($this->m_users->admin_add($this->input->post('username'))) {
								$this->session->set_flashdata('message_s','User added to administrators');
							} else {
								$this->session->set_flashdata('message_f','Something went wrong');
							}
						} elseif ($this->input->post('action') == 'remove') {
							if ($this->m_users->admin_remove($this->input->post('username'))) {
								$this->session->set_flashdata('message_s','User removed from administrators');

								// Check if removed user is currently logged user
								if ($this->session->userdata('username') == $this->input->post('username')) {
									$this->session->set_userdata('access', 1);
									redirect('/');
								}
							} else {
								$this->session->set_flashdata('message_f','Something went wrong');
							}
						}
					} else {
						$this->session->set_flashdata('message_f','User does not exist');
					}
				} else {
					foreach($this->form_validation->error_array() as $error) {
						$this->session->set_flashdata('message_f', $error);
						break;
					}
				}

				redirect('/users');

			} else {
				$this->session->set_flashdata('message_f', 'You don\'t have privileges to access this page');
				$this->redirect_back();
			}

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}

	public function ban_unban_user()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('access') == 3) {

				$this->load->library('form_validation');

				$this->form_validation->set_rules('username', 'Username', 'required|trim');
				$this->form_validation->set_rules('action', 'Action', 'required|trim');

				if ($this->form_validation->run()) {
					if ($this->m_users->get_user_id($this->input->post('username'))) {
						if ($this->input->post('action') == 'ban') {
							if ($this->m_users->user_ban($this->input->post('username'))) {
								$this->session->set_flashdata('message_s','User has been banned');
							} else {
								$this->session->set_flashdata('message_f','Something went wrong');
							}
						} elseif ($this->input->post('action') == 'unban') {
							if ($this->m_users->admin_remove($this->input->post('username'))) {
								$this->session->set_flashdata('message_s','User has been unbaned');

								// Check if removed user is currently logged user
								if ($this->session->userdata('username') == $this->input->post('username')) {
									$this->session->set_userdata('access', 0);
								}
							} else {
								$this->session->set_flashdata('message_f','Something went wrong');
							}
						}
					} else {
						$this->session->set_flashdata('message_f','User does not exist');
					}
				} else {
					foreach($this->form_validation->error_array() as $error) {
						$this->session->set_flashdata('message_f', $error);
						break;
					}
				}

				redirect('/users');

			} else {
				$this->session->set_flashdata('message_f', 'You don\'t have privileges to access this page');
				$this->redirect_back();
			}

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}
}