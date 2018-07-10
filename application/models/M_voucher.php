<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_voucher extends CI_Model {

	public function get_all($status)
	{
		$this->db->order_by('added', 'desc');

		if (isset($status)) {
			$this->db->where('status', $status);
		}
		
		$query = $this->db->get('voucher');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function add($user_id, $v_type, $p_type, $amount, $voucher)
	{
		if ($p_type == 'vfc' || $p_type == 'topu') {
			$amount = $amount * 100;
		}

		$data = array(
			'user_id'		=> $user_id,
			'voucher_type'	=> $v_type,
			'product_type'	=> $p_type,
			'amount'		=> $amount,
			'voucher'		=> $voucher
		);

		$query = $this->db->insert('voucher', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function resolve($id, $status)
	{
		$data = array(
			'resolved' => 1
		);

		// Updated time
		$this->db->set('added', 'NOW()', false);

		$data['status'] = $status;

		$this->db->where('id', $id);

		$query = $this->db->update('voucher', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}