<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_posts extends CI_Model {

	public function list_last_posts($limit = 10, $offset = 0)
	{
		$this->db->where('display', 1);
		$this->db->where('post_id', 0);
		$this->db->where('trending', 0);
		$this->db->limit($limit);
		$this->db->offset($offset);
		$this->db->order_by('posted', 'desc');

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function list_last_posts_by_user($user)
	{
		$this->db->where('display', 1);
		$this->db->where('post_id', 0);
		$this->db->where('user_id', $user);
		$this->db->order_by('posted', 'desc');

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function list_billboard($limit = 9)
	{
		$this->db->where('display', 1);
		$this->db->where('LENGTH(image)>0', null, false);
		$this->db->order_by('id', 'desc');
		if ($limit > 0)
			$this->db->limit($limit);

		$this->db->join('billboard', 'billboard.post_id = posts.id');

		$this->db->where('TIMEDIFF(billboard.time_left, TIMEDIFF(NOW(), billboard.activated)) >', '00:00:00');
		$this->db->where('billboard.active', 1);

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function list_trending($limit = 10, $offset = 0)
	{
		$this->db->where('display', 1);
		$this->db->where('trending', 1);
		$this->db->where('post_id', 0);
		$this->db->limit($limit);
		$this->db->offset($offset);
		$this->db->order_by('id', 'desc');
		$this->db->limit($limit);

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function get_post($post_id)
	{
		$this->db->where('display', 1);
		$this->db->where('id', $post_id);

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function insert_comment($reply = null, $image, $text, $access = 1)
	{
		$data = array(
			'user_id'	=> $this->session->all_userdata()['user_id'],
	   		'text'		=> $text
		);

		if (isset($reply)) {
			$data['post_id'] = $reply;
		}

		if (isset($image)) {
			$data['image'] = $image;
		}

		if ($access > 1) {
			$data['trending'] = 1;
		}

		$query = $this->db->insert('posts', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function update_comment($post_id, $text, $image = null)
	{
		$data = array(
			'user_id'	=> $this->session->all_userdata()['user_id'],
	   		'text'		=> $text,
	   		'display'		=> 1
		);

		// Updated time
		$this->db->set('posted', 'NOW()', false);

		if (isset($image)) {
			$data['image'] = $image;
		}

		$this->db->where('id', $post_id);

		$query = $this->db->update('posts', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function delete_comment($post_id)
	{
		$data = array(
			'id'	=>	$post_id
		);

		$this->db->delete('posts', $data);

		if ($this->db->affected_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function get_sub_posts($post_id)
	{
		$this->db->where('post_id', $post_id);
		$this->db->order_by('posted', 'desc');

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return;
		}
	}

	public function get_images_from_user($user_id)
	{
		$this->db->where('LENGTH(image)>0', null, false);
		$this->db->where('user_id', $user_id);
		$this->db->where('display', 1);
		$this->db->order_by('posted', 'desc');

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function get_posts_from_user($user_id)
	{
		$this->db->where('image IS NULL', null, false);
		$this->db->where('post_id', 0);
		$this->db->where('user_id', $user_id);
		$this->db->where('display', 1);
		$this->db->order_by('posted', 'desc');

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function get_post_from_user($post_id)
	{
		$this->db->where('user_id', $this->session->all_userdata()['user_id']);
		$this->db->where('id', $post_id);

		$query = $this->db->get('posts');

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function user_has_post($post_id)
	{
		$this->db->where('user_id', $this->session->all_userdata()['user_id']);
		$this->db->where('id', $post_id);

		$query = $this->db->get('posts');

		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function can_add_image()
	{
		$this->db->where('user_id', $this->session->all_userdata()['user_id']);
		$this->db->where('LENGTH(image)>0', null, false);

		$query = $this->db->get('posts');

		if ($query->num_rows() >= 0 && $query->num_rows() <= 15) {
			return true;
		} else {
			return false;
		}	
	}

	public function get_user_by_post($post_id)
	{
		$this->db->where('id', $post_id);

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			return $query->row()->user_id;
		} else {
			return false;
		}
	}
}