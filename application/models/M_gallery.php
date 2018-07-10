<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_gallery extends CI_Model {

	public function get_gallery_price($user_id)
	{
		$this->db->where('user_id', $user_id);

		$query = $this->db->get('gallery');

		if ($query->num_rows() > 0) {
			return $query->row()->price;
		} else {
			return 0;
		}
	}

	public function check_if_user_has_access($user_access, $user_gallery)
	{
		$this->db->where('user_access', $user_access); // Current user trying to access other user's gallery
		$this->db->where('user_gallery', $user_gallery); // Other user

		$query = $this->db->get('gallery_access');

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function add_gallery_price($user_id, $price)
	{
		$this->db->where('user_id', $user_id);

		$query = $this->db->get('gallery');

		if ($query->num_rows() > 0) {
			// Update

			$data = array(
				'price'		=> $price
			);

			$this->db->where('user_id', $user_id);

			$query = $this->db->update('gallery', $data);

			if ($query) {
				return true;
			} else {
				return false;
			}
			
		} else {
			// Insert

			$data = array(
				'user_id'	=> $user_id,
				'price'		=> $price
			);

			$query = $this->db->insert('gallery', $data);

			if ($query) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function unlock_gallery($user_access, $user_gallery)
	{
		$data = array(
			'user_access'	=> $user_access,
			'user_gallery'	=> $user_gallery
		);

		$query = $this->db->insert('gallery_access', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}