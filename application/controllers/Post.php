<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_posts');
		$this->load->model('m_users');
		$this->load->model('m_wallet');
		$this->load->model('m_billboard');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {

			$this->load->library('form_validation');
			$this->load->helper('string');

			$this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('reply_id', '', 'trim');
			$this->form_validation->set_rules('zone', '', 'trim');

			$config['upload_path'] 		= './images/';
			$config['allowed_types']	= 'gif|jpg|png';
			$config['max_size']			= '2048';
			$config['max_width'] 		= '1920';
			$config['max_height'] 		= '1080';
			$config['file_name'] 		= random_string('alnum', 8).'_'.random_string('alnum', 8);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$image = null;

			if (!$this->upload->do_upload('file')) {
				if ("You did not select a file to upload." != $this->upload->display_errors('','')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('message_f', $error['error']);
					redirect('/');
				}
			} else {
				$image = $this->upload->data()['file_name'];
			}

			$access = $this->session->userdata('access');

			if ($this->form_validation->run()) {

				$user_id = $this->session->userdata('user_id');

				// If there is a reply id, it's a subcomment
				if ($this->input->post('reply_id') > 0) {

					$post_id = $this->input->post('reply_id');

					// Check if user has less than 15 images
					if ($image && !$this->m_posts->can_add_image()) {
						$this->session->set_flashdata('message_f','You have reached your 15 photos limit');
					} else {

						// 10kobo for comment in trending, in free zone it's free
						$post_trending = $this->m_posts->get_post($post_id)[0]->trending;
						if ($post_trending > 0) {
							$post_worth = 10;
						} else {
							$post_worth = 0;
						}

						// Check if user has certan amount to be decudeted from his wallet
						if ($this->m_wallet->check_if_user_has_amount($user_id, $post_worth, 'wallet_topup')) {
							$wallet = 'wallet_topup';
						} elseif ($this->m_wallet->check_if_user_has_amount($user_id, $post_worth, 'wallet_free')) {
							$wallet = 'wallet_free';
						} elseif ($this->m_wallet->check_if_user_has_amount($user_id, $post_worth, 'wallet')) {
							$wallet = 'wallet';
						}

						$user_to = $this->m_posts->get_post($post_id)[0]->user_id;

						if ($wallet) {

							// Post comment
							$this->m_posts->insert_comment($post_id, $image, $this->no_xss($this->input->post('message')), $access);

							// Deduct money from current user
							$this->m_wallet->deduct_amount($user_id, $post_worth, $wallet);

							// Add money to users
							
							$this->m_wallet->add_amount($user_id, $user_to, $post_worth, 'wallet', 2);

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
							
							if ($email != $this->m_users->get_user_info($user_to)->email) {
								$this->email->to($email);
								$this->email->subject("You have earned $value kobo");
								$message = "<p>You have earned <strong>$value</strong> kobo from <a href=\"".base_url()."user/".$this->m_users->get_user_info($user_to)->username."\">".$this->m_users->get_user_info($user_to)->username."<a/></strong>.</p>";
								$this->email->message($message);

								$this->email->send();
							}
							/* End notification */

							$this->session->set_flashdata('message_s','Posted successfully');

						} else {
							
							$this->session->set_flashdata('message_f','Not enough funds to post');
						}
					}
				}
				// ...but if there is no reply id, then it's regular comment
				else
				{
					if ($image && !$this->m_posts->can_add_image()) {
						$this->session->set_flashdata('message_f','You have reached your 15 photos limit');
					} else {

						// Check if user is PAID user and have selected TRENDING, so post to trending zone is free
						if ($this->session->userdata('access') > 1 && $this->input->post('zone') == 't')
						{
							if ($this->m_posts->insert_comment(null, $image, $this->no_xss($this->input->post('message')), $access)) {
								$this->session->set_flashdata('message_s','Posted successfully (trending)');
							}
						}
						else 
						{
							if ($this->m_posts->insert_comment(null, $image, $this->no_xss($this->input->post('message')), 1)) {
								$this->session->set_flashdata('message_s','Posted successfully (free)');
							}
						}	
					}
				}
			} else {
				foreach($this->form_validation->error_array() as $error) {
					$this->session->set_flashdata('message_f', $error);
					break;
				}
			}

			$this->redirect_back();
			
		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
		}

		redirect('/');
	}

	public function p()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('not_updated')) {
				$this->session->set_flashdata('message_i', 'Add your first name, surname and phone number');
				redirect('/settings');
			} else {
				$header['updated'] = 1;
			}

			$header['access'] = $this->session->userdata('access');
			$header['user'] = $this->session->userdata('username');
			$header['user'] = $this->m_users->get_user_info($this->session->userdata('user_id'));

			$data_1['user'] = $header['user'];
			$data_1['gallery'] = $this->m_posts->get_images_from_user($this->session->userdata('user_id'));

			$post_id = $this->uri->segment(3);

			$data_2['show_comments'] = 1;
			$data_2['access'] = $this->session->userdata('access');

			if ($data_2['posts'] = $this->m_posts->get_post($post_id))
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
					$header['title'] = $post->text;
					$header['description'] = $post->text;
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
			} else {
				$this->redirect_back();
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
					if ($this->m_billboard->check_if_active($post->id)) $post->billboard = 1;
					$post->comments = count($this->m_posts->get_sub_posts($post->id));
				}
			}

			$data_2['is_logged_in'] = 1;
			$data_2['post_page'] = true;

			if (!isset($header['description'])) $header['description'] = '100kobo is a social network where users earn money with their contents.';
			$header['site_name'] = '100kobo';
			$header['url'] = site_url().'p';
			
			$this->load->view('home/header', $header);
			$this->load->view('home/left-container', $data_1);
			$this->load->view('home/center-container', $data_2);
			$this->load->view('home/right-container', $data_3);
			$this->load->view('home/footer');
		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}
}