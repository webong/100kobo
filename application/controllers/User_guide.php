<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_guide extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_posts');
		$this->load->model('m_gallery');
		$this->load->model('m_billboard');
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

			$header['title'] = 'User Guide';
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

			$header['description'] = 'Signing up with 100 kobo is the first step to take while using our services, after which you can begin to enjoy our numerous services';
			$header['site_name'] = '100kobo';
			$header['url'] = site_url().'user-guide';

			$this->load->view('home/header', $header);
			$this->load->view('home/left-container', $data_1);
			$this->load->view('special/user_guide');
			$this->load->view('home/right-container', $data_3);
			$this->load->view('home/footer');

		} else {

			$header['title'] = 'User Guide';
			$header['description'] = 'Signing up with 100 kobo is the first step to take while using our services, after which you can begin to enjoy our numerous services';
			$header['site_name'] = '100kobo';
			$header['url'] = site_url().'user-guide';

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

			$this->load->view('login/header', $header);
			$this->load->view('login/left-container');
			$this->load->view('special/user_guide');
			$this->load->view('home/right-container', $data_3);
			$this->load->view('login/footer');
		}
	}
}