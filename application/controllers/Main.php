<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_posts');
		$this->load->model('m_billboard');
	}
	
	public function index()
	{
		$data_2['access'] = $this->session->userdata('access');
		
		if ($data_2['posts'] = $this->m_posts->list_last_posts(10))
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

			$data_2['load_more'] = 1;
		}

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
		$header['url'] = site_url();

		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('not_updated')) {
				$this->session->set_flashdata('message_i', 'Add your first name, surname and phone number in <a href="'.base_url().'settings">Settings</a>');
			} else {
				$header['updated'] = 1;
			}

			$header['title'] = 'Home';
			$header['access'] = $this->session->userdata('access');
			$header['user'] = $this->session->userdata('username');
			$header['user_grup'] = $this->m_users->get_user_info($this->session->userdata('access'));
			$header['user'] = $this->m_users->get_user_info($this->session->userdata('user_id'));

			$data_1['user'] = $header['user'];
			$data_1['gallery'] = $this->m_posts->get_images_from_user($this->session->userdata('user_id'));

			$data_2['is_logged_in'] = 1;
			$data_3['is_logged_in'] = 1;

			$this->load->view('home/header', $header);
			$this->load->view('home/left-container', $data_1);
			$this->load->view('home/center-container', $data_2);
			$this->load->view('home/right-container', $data_3);
			$this->load->view('home/footer');
		} else {
			$this->load->view('login/header', $header);
			$this->load->view('login/left-container');
			$this->load->view('home/center-container', $data_2);
			$this->load->view('home/right-container', $data_3);
			$this->load->view('login/footer');
		}
	}

	public function loadMorePosts($offset = 0)
	{
        $posts = $this->m_posts->list_last_posts(10, $offset);

        if ($posts) {
	        foreach ($posts as $post) {

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

	        	echo '<div class="row post">';
				echo '	<div class="left-post">';
				echo '		<img src="'; if ($post->profile_image) echo base_url().'images/'.$post->profile_image; else echo base_url().'assets/img/no-profile.png'; echo '" class="profile" />';
				echo '	</div>';
				echo '	<div class="right-post">';
				echo '		<div class="line">';
				echo '			<a href="'.base_url()."user/".$post->username.'"><h5>'.$post->first_name.' '.$post->surname.'</h5></a>';
				echo '			<a href="'.base_url().'post/p/'.$post->id.'"><span class="time-ago">'.$post->time_ago.'</span></a>';
				echo '			<span class="hidden" id="mrk">'.$post->id.'</span>';
				echo '		</div>';
				echo '		<div class="line">';
				echo '			<p class="post_text">';
	            echo '          	<span class="line-text">'.$post->text.'</span>';
	            					if (strlen($post->image) > 1) {
	            					    echo '<a href="'.base_url().'images/'.$post->image.'" data-title="'.$post->text.'" data-lightbox="'.$post->id.'"><img src="'.base_url().'images/'.$post->image.'" class="post_image" /></a>';
	            					}
	            echo '            </p>';
				echo '		</div>';
				echo '	</div>';
				echo '	<div class="bottom-post">';
				echo '		<button class="anchor" data-toggle="collapse" data-target="#sub-'.$post->id.'"><span class="glyphicon glyphicon-comment"></span>'; if($post->comments > 0) echo '<span class="count-comments">'.$post->comments.'</span>'; echo '</button>';
				echo '		<button class="anchor" data-toggle="modal" data-target="#modal-'; if (isset($is_logged_in) && $is_logged_in == 1) echo $post->id; else echo 'login'; echo '" onclick="set_validate(\'N'.number_format($post->comment_worth, 2).'\')"><span class="glyphicon glyphicon-pencil"></span></button>';
							if ($this->session->userdata('user_id') && $post->user_id == $this->session->userdata('user_id')) :
				echo '			<a href="'.base_url().'delete/post/'.$post->id.'" class="btn btn-xs btn-danger btn-m" onclick="return confirm(\'Are you sure you want to delete this post?\');">Delete</a>';
								if (strlen($post->image) > 1 && $post->billboard == 0) :
	    	    echo '                <a href="'.base_url().'edit/add_billboard/'.$post->id.'" class="btn btn-xs btn-default btn-m">Add to billboard</a>';
	    	    				elseif (strlen($post->image) > 1 && $post->billboard == 1) :
	    	    echo '                <a href="'.base_url().'edit/remove_billboard/'.$post->id.'" class="btn btn-xs btn-danger btn-m">Remove from billboard</a>';
		        				endif;
	            echo '          <button type="button" class="btn btn-xs btn-default btn-m" data-toggle="modal" data-target="#modal-edit">Edit</button>';
	            			endif;
				echo '	</div>';
	      		echo '</div><!-- /.post -->';

	  				if (isset($post->sub_post)) :
	      		echo '<div class="sub-posts collapse '; if(isset($show_comments)) echo 'in'; echo '" id="sub-'.$post->id.'">';
	      				foreach ($post->sub_post as $sub_post) :
	      		echo '	<div class="row sub-post">';
				echo '		<div class="left-post">';
				echo '			<img src="'; if ($sub_post->profile_image) echo base_url().'images/'.$sub_post->profile_image; else echo base_url().'assets/img/no-profile.png'; echo '" class="profile" />';
				echo '		</div>';
				echo '		<div class="right-post">';
				echo '			<div class="line">';
				echo '				<a href="'.base_url()."user/".$sub_post->username.'"><h5>'.$sub_post->first_name.' '.$sub_post->surname.'</h5></a>';
				echo '				<a href="'.base_url().'post/p/'.$post->id.'"><span class="time-ago">'.$sub_post->time_ago.'</span></a>';
				echo '				<span class="hidden">'.$sub_post->id.'</span>';
				echo '			</div>';
				echo '			<div class="line">';
				echo '				<p class="post_text">';
	            echo '              	<span class="line-text">'.$sub_post->text.'</span>';
	            						if (strlen($sub_post->image) > 1) {
	            echo '                      <a href="'.base_url().'images/'.$sub_post->image.'" data-title="'.$sub_post->text.'" data-lightbox="'.$sub_post->id.'"><img src="'.base_url().'images/'.$sub_post->image.'" class="post_image" /></a>';
	            						}
	            echo '                </p>';
				echo '			</div>';
				echo '		</div>';
							if ($this->session->userdata('user_id') && $sub_post->user_id == $this->session->userdata('user_id')) :
	            echo '      		<div class="bottom-post">';
	            echo '                <a href="'.base_url().'delete/post/'.$sub_post->id.'" class="btn btn-xs btn-danger btn-m" onclick="return confirm(\'Are you sure you want to delete this post?\');">Delete</a>';
	            echo '                <button type="button" class="btn btn-xs btn-default btn-m" data-toggle="modal" data-target="#modal-edit">Edit</button>';
	            echo '      		</div>';
	            			endif;
				echo '	</div><!-- /.sub-post -->';
						endforeach;
	      		echo '</div><!-- /.sub-posts -->';
	      			endif;

	      		echo '<div id="modal-'.$post->id.'" class="modal fade" role="dialog">';
				echo '	<div class="modal-dialog">';
				echo '		<!-- Modal content-->';
				echo '		<div class="modal-content">';
								$args = array(
									'id'		=> $post->id,
									'onsubmit'	=> 'return validate(this)'
								);
				echo 			form_open_multipart('post', $args);
				echo '		  	<div class="modal-header">';
				echo '		    	<button type="button" class="close" data-dismiss="modal">&times;</button>';
				echo '		    	<h4 class="modal-title">Write a comment to '.$post->first_name.' '.$post->surname.'</h4>';
				echo '		  	</div>';
				echo '		  	<div class="modal-body post">';
				echo '		    	<div class="line">';
				echo '					<h5>Message</h5>';
				echo '					<span id="textarea_feedback" class="counter align-right"></span>';
				echo '					<textarea name="message" id="textarea" class="form-control" maxlength="160" placeholder="Message..."></textarea>';
				echo '				</div>';
				echo '				<div class="line push-top-2 push-bottom-1">';
				echo '					<h5>Upload image (optional)</h5>';
				echo '					<input type="file" name="file" size="20" />';
				echo '				</div>';
				echo '		  	</div>';
				echo '		  	<div class="modal-footer">';
				echo 		  		form_hidden('reply_id', $post->id);
									$args = array('class' => 'btn btn-blue align-right');
				echo 		  		form_submit('submit', 'Post', $args);
				echo '		  	</div>';
				echo 		  	form_close();
				echo '		</div>';
				echo '	</div>';
				echo '</div>';
	        }
	    }
        
        exit;
	}

	public function loadMoreTrendings($offset = 0)
	{
		$posts = $this->m_posts->list_trending(10, $offset);
		$access = $this->session->userdata('access');

		foreach ($posts as $post) {
			$temp_user = new stdClass();
			$temp_user = $this->m_users->get_user_info($post->user_id);
			$post->username = $temp_user->username;
			$post->first_name = $temp_user->first_name;
			$post->surname = $temp_user->surname;
			$post->profile_image = $temp_user->image;
			$post->time_ago = $this->time_elapsed_string($post->posted);
			$post->comment_worth = $this->post_worth_trending($post->id) / 100;
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

			echo '<div class="row post-mini">';
			echo '	<div class="line">';
			echo '		<a href="'.base_url()."user/".$post->username.'"><h5>'.$post->first_name.' '.$post->surname.'</h5></a>';
			echo '	</div>';
			echo '	<div class="line">';
			echo '		<p>';
            echo '        <span class="line-text">'.$post->text.'</span>';
            				if (strlen($post->image) > 1) {
            echo '          	<a href="'.base_url().'images/'.$post->image.'" data-title="'.$post->text.'" data-lightbox="'.$post->id.'"><img src="'.base_url().'images/'.$post->image.'" class="post_image" /></a>';
            				}
            echo '        </p>';
			echo '	</div>';
			echo '	<div class="line push-top-4">';
			echo '		<a href="'.base_url().'post/p/'.$post->id.'"><span class="time-ago">'.$post->time_ago.'</span></a>';
			echo '	</div>';
            echo '  <div class="bottom-post">';
            echo '        <button class="anchor" data-toggle="modal" data-target="#modal-'; if ($access > 1) echo $post->id; elseif ($access == 1) echo 'paid-user'; else echo 'login'; echo '" onclick="set_validate(\'N'.number_format($post->comment_worth, 2).'\')"><span class="glyphicon glyphicon-pencil"></span></button>';
            echo '        <span class="text-muted align-right number-n">N'.number_format($post->comment_worth, 2).'</span>';
            echo '    </div>';
        			if (isset($post->sub_post)) :
            echo '    <div class="sub-right">';
            				foreach ($post->sub_post as $sub_post) :
            echo '            <div class="post-mini" id="sub-'.$post->id.'">';
            echo '                <div class="left-side">';
            echo '                    <span class="glyphicon glyphicon-chevron-right"></span>';
            echo '                </div>';
            echo '                <div class="right-side">';
            echo '                    <div class="line">';
            echo '                        <a href="'.base_url()."user/".$sub_post->username.'"><h5>'.$sub_post->first_name.' '.$sub_post->surname.'</h5></a>';
            echo '                    </div>';
            echo '                    <div class="line">';
            echo '                        <p>';
            echo '                        	<span class="line-text">'.$sub_post->text.'</span>';
            								if (strlen($sub_post->image) > 1) {
            echo '                          	<a href="'.base_url().'images/'.$sub_post->image.'" data-title="'.$sub_post->text.'" data-lightbox="'.$sub_post->id.'"><img src="'.base_url().'images/'.$sub_post->image.'" class="post_image" /></a>';
            								}
            echo '                        </p>';
            echo '                    </div>';
            echo '                    <div class="line push-top-4">';
            echo '                        <a href="'.base_url().'post/p/'.$sub_post->id.'"><span class="time-ago">'.$sub_post->time_ago.'</span></a>';
            echo '                    </div>';
            echo '                </div>';
            echo '            </div><!-- /.post-mini -->';
            				endforeach;
            echo '    </div>';
        			endif;
      		echo '</div><!-- /.post-mini -->';

            echo '<div id="modal-'.$post->id.'" class="modal fade" role="dialog">';
            echo '    <div class="modal-dialog">';
            echo '        <!-- Modal content-->';
            echo '        <div class="modal-content">';
        					$args = array(
            				      'id'        => $post->id,
            					  'onsubmit'  => 'return validate(this)'
            				);
            echo 			form_open_multipart('post', $args);
            echo '            <div class="modal-header">';
            echo '                <button type="button" class="close" data-dismiss="modal">&times;</button>';
            echo '                <h4 class="modal-title">Write a comment to '.$post->first_name.' '.$post->surname.'</h4>';
            echo '            </div>';
            echo '            <div class="modal-body post">';
            echo '                <div class="line">';
            echo '                    <h5>Message</h5>';
            echo '                    <span id="textarea_feedback" class="counter align-right"></span>';
            echo '                    <textarea name="message" id="textarea" class="form-control" maxlength="160" placeholder="Message..."></textarea>';
            echo '                </div>';
            echo '                <div class="line push-top-2 push-bottom-1">';
            echo '                    <h5>Upload image (optional)</h5>';
            echo '                    <input type="file" name="file" size="20" />';
            echo '                </div>';
            echo '            </div>';
            echo '            <div class="modal-footer">';
            echo 				  form_hidden('reply_id', $post->id);
            echo 				  $args = array('class' => 'btn btn-blue align-right');
            echo 				  form_submit('submit', 'Post', $args);
            echo '            </div>';
            echo 			  form_close();
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
		}
	}
}