<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_withdraws extends CI_Model {

	public function can_withdraw($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->where('LENGTH(account_name)>0', null, false);
		$this->db->where('LENGTH(bank_name)>0', null, false);
		$this->db->where('LENGTH(account_number)>0', null, false);

		$query = $this->db->get('users');

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function add_withdraw($user_id, $amount)
	{

		// Add withdraw request
		$data = array(
			'user_id'			=> $this->session->all_userdata()['user_id'],
	   		'amount'			=> $amount
		);

		$query = $this->db->insert('withdraws', $data);

		if ($query) {

			// Store in wallet_history for tracking purposes
			$data = array(
				'user_from'	=> $this->session->all_userdata()['user_id'],
				'user_to'	=> 0,
		   		'amount'	=> $amount,
		   		'type'		=> 5
			);

			$this->db->insert('wallet_history', $data);

			return true;

		} else {
			return false;
		}
	}

	public function get_withdraws($solved = -1)
	{
		/*
		* Solved state:
		* 	-1 for all
		* 	0 for non solved
		* 	1 for solved
		*
		*/
		if ($solved >= 0) {
			$this->db->where('solved', $solved);
		}

		$this->db->select('*');
		$this->db->from('withdraws as t1');
		$this->db->order_by('t1.id', 'desc');
		$this->db->join('users as t2', 't1.user_id = t2.id');

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function get_withdraw($id)
	{
		$this->db->where('id', $id);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function mark_as_solved($id, $user_id, $status)
	{
		$data = array(
			'solved' => 1,
			'status' => $status
		);

		$this->db->where('id', $id);
		$this->db->where('user_id', $user_id);

		// Set solved time
		$this->db->set('solved_date', 'NOW()', false);

		$query = $this->db->update('withdraws', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}