<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Paystack extends CI_Model {

	public function add($user_id, $amount, $reference)
	{
		$data = array(
			'user_id'	=> $user_id,
			'amount'	=> $amount,
			'reference'	=> $reference
		);

		$query = $this->db->insert('paystack', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function markAsDone($reference)
	{
		$data = array(
			'done'	=> 1
		);

		$this->db->where('reference', $reference);

		$query = $this->db->update('paystack', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function getData($reference)
	{
		$this->db->where('reference', $reference);

		$query = $this->db->get('paystack');

		if ($query->num_rows() > 0) {
			return $query->result()[0];
		} else {
			return false;
		}
	}
}