<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Our_Mission extends MY_Controller {

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

			$header['title'] = 'Our Mission';
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

			$header['description'] = 'Our mission is to enhance your creativity, make your work count and useful to others. Our goal: To bring back the existence of the Nigerian kobo and make it useful again.';
			$header['site_name'] = '100kobo';
			$header['url'] = site_url().'our-mission';

			$this->load->view('home/header', $header);
			$this->load->view('home/left-container', $data_1);
			$this->load->view('special/our_mission');
			$this->load->view('home/right-container', $data_3);
			$this->load->view('home/footer');

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}
}