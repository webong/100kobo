<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_billboard extends CI_Model {

	public function check_if_exist($post_id)
	{
		$this->db->where('post_id', $post_id);

		$query = $this->db->get('billboard');

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function check_if_active($post_id)
	{
		$this->db->where('post_id', $post_id);
		$this->db->where('TIMEDIFF(time_left, TIMEDIFF(NOW(), activated)) >', '00:00:00');
		$this->db->where('active', 1);

		$query = $this->db->get('billboard');

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function check_if_can_activate($post_id)
	{
		$this->db->where('time_left >', '00:00:00');
		$this->db->where('post_id', $post_id);

		$query = $this->db->get('billboard');

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function add_image($post_id)
	{
		$data = array(
			'post_id'	=> $post_id,
			'active'	=> 1
		);

		$query = $this->db->insert('billboard', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function mark_inactive($post_id)
	{
		$this->db->where('post_id', $post_id);
		$this->db->where('active', 1);
		// SELECT activated, NOW() as now, TIMEDIFF(time_left, TIMEDIFF(NOW(), activated)) as time_left_new FROM `billboard` WHERE 1 
		$this->db->select('TIMEDIFF(time_left, TIMEDIFF(NOW(), activated)) as time_left_new');

		$query = $this->db->get('billboard');

		if ($query->num_rows() > 0) {
			$image = $query->row();

			$data = array(
				'time_left'	=> $image->time_left_new,
				'active'	=> 0
			);

			$this->db->where('post_id', $post_id);

			$query = $this->db->update('billboard', $data);

			if ($this->db->affected_rows() > 0) {
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}

	public function mark_active($post_id)
	{
		$this->db->where('post_id', $post_id);
		$this->db->where('active', 0);

		$data = array(
			'active'	=> 1
		);

		$this->db->set('activated', 'NOW()', false);

		$query = $this->db->update('billboard', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function mark_finished($post_id)
	{
		$this->db->where('post_id', $post_id);

		$data = array(
			'time_left'	=> '00:00:00',
			'active'	=> 1
		);

		$query = $this->db->update('billboard', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function reset_billboard($post_id)
	{
		$this->db->where('post_id', $post_id);

		$this->db->set('activated', 'NOW()', FALSE);

		$data = array(
			'post_id'	=> $post_id,
			'time_left'	=> '24:00:00',
			'active'	=> 1
		);

		$query = $this->db->update('billboard', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function check_all()
	{
		$this->db->where('TIMEDIFF(time_left, TIMEDIFF(NOW(), activated)) <=', '00:00:00');
		$this->db->where('active', 1);

		$data = array(
			'time_left'	=> '00:00:00',
			'active'	=> 0
		);

		$query = $this->db->update('billboard', $data);

		if ($this->db->affected_rows() > 0) {
			return $this->db->affected_rows();
		} else {
			return false;
		}
	}

	public function get_all_active()
	{
		$this->db->where('TIMEDIFF(time_left, TIMEDIFF(NOW(), activated)) >', '00:00:00');

		$query = $this->db->get('billboard');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
}