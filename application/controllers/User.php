<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

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
		redirect('/');
	}

	public function profile()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('not_updated')) {
				$this->session->set_flashdata('message_i', 'Add your first name, surname and phone number');
				redirect('/settings');
			} else {
				$header['updated'] = 1;
			}

			if ($profile_id = $this->m_users->get_user_id($this->uri->segment(2))) {
				$header['title'] = $this->uri->segment(2).'\'s profile';
				$header['access'] = $this->session->userdata('access');
				$header['user'] = $this->session->userdata('username');
				$header['url'] = site_url().'user/'.$this->uri->segment(2);

				$header['user'] = $this->m_users->get_user_info($this->session->userdata('user_id'));

				$data_1['user'] = $this->m_users->get_user_info($profile_id);
				$data_1['gallery'] = $this->m_posts->get_images_from_user($profile_id);

				$header['description'] = $data_1['user']->about;

				if ($this->session->userdata('user_id') === $profile_id)
					$data_1['show_report'] = false;
				else 
					$data_1['show_report'] = true;


				$data_2['g_empty'] = true;
				$data_2['p_empty'] = true;

				if ($this->m_posts->get_images_from_user($profile_id)) {
					$data_2['g_empty'] = false;
				}

				if ($this->m_posts->get_posts_from_user($profile_id)) {
					$data_2['p_empty'] = false;
				}

				// Check if user has privilegue to access other user's gallery
				if ($profile_id == $this->session->userdata('user_id') || 
					$this->m_gallery->check_if_user_has_access($this->session->userdata('user_id'), $profile_id) ||
					$this->m_gallery->get_gallery_price($profile_id) == 0 ||
					$data_2['g_empty'])
				{
					$data_1['g_access'] = $data_2['g_access'] = true;
				} else {
					$data_1['g_access'] = $data_2['g_access'] = false;
					$data_2['g_price'] = $this->m_gallery->get_gallery_price($profile_id) / 100;
					$data_2['g_user_id'] = $profile_id;
				}

				if ($data_2['posts'] = $this->m_posts->list_last_posts_by_user($profile_id))
				{
					foreach ($data_2['posts'] as $post) {
						$temp_user = new stdClass();
						$temp_user = $this->m_users->get_user_info($post->user_id);
						$post->username = $temp_user->username;
						$post->first_name = $temp_user->first_name;
						$post->surname = $temp_user->surname;
						$post->profile_image = $temp_user->image;
						$post->time_ago = $this->time_elapsed_string($post->posted);
						$post->comment_worth = $this->post_worth($post->id) / 100;
						if ($this->m_billboard->check_if_active($post->id)) $post->billboard = 1;
						$i = 0;

						$temp_sub_post = new stdClass();
						if ($temp_sub_post = $this->m_posts->get_sub_posts($post->id)) {
							foreach ($temp_sub_post as $sub_post) {
								$post->sub_post[] = $sub_post;
								$temp_user = $this->m_users->get_user_info($sub_post->user_id);
								$sub_post->username = $temp_user->username;
								$sub_post->first_name = $temp_user->first_name;
								$sub_post->surname = $temp_user->surname;
								$sub_post->profile_image = $temp_user->image;
								$sub_post->time_ago = $this->time_elapsed_string($sub_post->posted);
								if ($this->m_billboard->check_if_active($sub_post->id)) $sub_post->billboard = 1;
								$i++;
							}
						}
						$post->comments = $i;
					}
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

						$temp_sub_post = new stdClass();
						if ($temp_sub_post = $this->m_posts->get_sub_posts($post->id)) {
							foreach ($temp_sub_post as $sub_post) {
								$post->sub_post[] = $sub_post;
								$temp_user = $this->m_users->get_user_info($sub_post->user_id);
								$sub_post->username = $temp_user->username;
								$sub_post->first_name = $temp_user->first_name;
								$sub_post->surname = $temp_user->surname;
								$sub_post->profile_image = $temp_user->image;
								$sub_post->time_ago = $this->time_elapsed_string($sub_post->posted);
								if ($this->m_billboard->check_if_active($sub_post->id)) $sub_post->billboard = 1;
							}
						}
					}
				}

				$data_2['is_logged_in'] = 1;

				if (!isset($header['description'])) $header['description'] = '100kobo is a social network where users earn money with their contents.';
				$header['site_name'] = '100kobo';
				if (!isset($header['url'])) $header['url'] = site_url();
				
				$this->load->view('home/header', $header);
				if ($profile_id == $this->session->userdata('user_id')) {
					$this->load->view('home/left-container', $data_1);
				} else {
					$this->load->view('user/left-container', $data_1);
				}
				$this->load->view('user/center-container', $data_2);
				$this->load->view('home/right-container', $data_3);
				$this->load->view('home/footer');
			}
			else {
				redirect('/');
			}
		} else {
			redirect('/');
		}
	}
}